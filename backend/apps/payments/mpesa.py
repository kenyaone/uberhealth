"""
M-Pesa Daraja API integration (STK Push / Lipa Na M-Pesa Online).
"""
import base64
import requests
from datetime import datetime
from django.conf import settings


def get_access_token() -> str:
    url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'
    if settings.MPESA_ENV == 'production':
        url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'

    response = requests.get(
        url,
        auth=(settings.MPESA_CONSUMER_KEY, settings.MPESA_CONSUMER_SECRET),
        timeout=30,
    )
    response.raise_for_status()
    return response.json()['access_token']


def generate_password() -> tuple:
    timestamp = datetime.now().strftime('%Y%m%d%H%M%S')
    raw = f"{settings.MPESA_SHORTCODE}{settings.MPESA_PASSKEY}{timestamp}"
    password = base64.b64encode(raw.encode()).decode()
    return password, timestamp


def format_phone(phone: str) -> str:
    phone = phone.strip().replace(' ', '').replace('-', '')
    if phone.startswith('0'):
        phone = '254' + phone[1:]
    if phone.startswith('+'):
        phone = phone[1:]
    return phone


def initiate_stk_push(phone: str, amount: int, reference: str, description: str) -> dict:
    if not settings.MPESA_CONSUMER_KEY:
        return {'CheckoutRequestID': 'SANDBOX-TEST-' + reference, 'ResponseDescription': 'Sandbox mode'}

    token = get_access_token()
    password, timestamp = generate_password()
    phone = format_phone(phone)

    url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest'
    if settings.MPESA_ENV == 'production':
        url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest'

    payload = {
        'BusinessShortCode': settings.MPESA_SHORTCODE,
        'Password': password,
        'Timestamp': timestamp,
        'TransactionType': 'CustomerPayBillOnline',
        'Amount': amount,
        'PartyA': phone,
        'PartyB': settings.MPESA_SHORTCODE,
        'PhoneNumber': phone,
        'CallBackURL': settings.MPESA_CALLBACK_URL,
        'AccountReference': reference,
        'TransactionDesc': description,
    }

    response = requests.post(
        url,
        json=payload,
        headers={'Authorization': f'Bearer {token}'},
        timeout=30,
    )
    response.raise_for_status()
    return response.json()
