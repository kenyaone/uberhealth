"""
M-Pesa Daraja B2C API — pay professionals their 80% share.
"""
import requests
from django.conf import settings
from .mpesa import get_access_token, format_phone


def send_b2c_payment(phone: str, amount: int, reference: str, remarks: str = 'Professional payout') -> dict:
    if not settings.MPESA_CONSUMER_KEY:
        return {'ResponseCode': '0', 'ResponseDescription': 'Sandbox mode — B2C simulated', 'ConversationID': f'SIM-{reference}'}

    token = get_access_token()
    phone = format_phone(phone)

    url = 'https://sandbox.safaricom.co.ke/mpesa/b2c/v3/paymentrequest'
    if settings.MPESA_ENV == 'production':
        url = 'https://api.safaricom.co.ke/mpesa/b2c/v3/paymentrequest'

    payload = {
        'OriginatorConversationID': reference,
        'InitiatorName': settings.MPESA_B2C_INITIATOR_NAME,
        'SecurityCredential': settings.MPESA_B2C_SECURITY_CREDENTIAL,
        'CommandID': 'BusinessPayment',
        'Amount': amount,
        'PartyA': settings.MPESA_SHORTCODE,
        'PartyB': phone,
        'Remarks': remarks,
        'QueueTimeOutURL': settings.MPESA_B2C_TIMEOUT_URL,
        'ResultURL': settings.MPESA_B2C_RESULT_URL,
        'Occasion': reference,
    }

    response = requests.post(
        url,
        json=payload,
        headers={'Authorization': f'Bearer {token}'},
        timeout=30,
    )
    response.raise_for_status()
    return response.json()
