from rest_framework import generics, status
from rest_framework.decorators import api_view, permission_classes
from rest_framework.permissions import IsAuthenticated, AllowAny
from rest_framework.response import Response
from django.db.models import Q
from .models import Professional, Specialization, Language
from .serializers import ProfessionalSerializer, ProfessionalRegisterSerializer, SpecializationSerializer, LanguageSerializer


class ProfessionalListView(generics.ListAPIView):
    serializer_class = ProfessionalSerializer
    permission_classes = [AllowAny]

    def get_queryset(self):
        qs = Professional.objects.filter(
            verification_status='verified',
            is_available_online=True,
            is_accepting_new_patients=True,
        ).select_related('user').prefetch_related('specializations', 'languages', 'availability')

        specialization = self.request.query_params.get('specialization')
        if specialization:
            qs = qs.filter(specializations__slug=specialization)

        min_rate = self.request.query_params.get('min_rate')
        max_rate = self.request.query_params.get('max_rate')
        if min_rate:
            qs = qs.filter(rate_per_hour__gte=min_rate)
        if max_rate:
            qs = qs.filter(rate_per_hour__lte=max_rate)

        gender = self.request.query_params.get('gender')
        if gender:
            qs = qs.filter(gender=gender)

        language = self.request.query_params.get('language')
        if language:
            qs = qs.filter(languages__name__icontains=language)

        search = self.request.query_params.get('search')
        if search:
            qs = qs.filter(
                Q(user__display_name__icontains=search) |
                Q(bio__icontains=search) |
                Q(specializations__name__icontains=search)
            )

        return qs.order_by('-rating', '-total_sessions').distinct()


class ProfessionalDetailView(generics.RetrieveAPIView):
    serializer_class = ProfessionalSerializer
    permission_classes = [AllowAny]
    queryset = Professional.objects.filter(verification_status='verified')


@api_view(['POST'])
@permission_classes([IsAuthenticated])
def register_professional(request):
    if hasattr(request.user, 'professional_profile'):
        return Response({'error': 'Professional profile already exists.'}, status=status.HTTP_400_BAD_REQUEST)

    serializer = ProfessionalRegisterSerializer(data=request.data)
    if serializer.is_valid():
        professional = serializer.save(user=request.user)
        request.user.role = 'professional'
        request.user.save()
        return Response(ProfessionalSerializer(professional).data, status=status.HTTP_201_CREATED)
    return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def my_professional_profile(request):
    try:
        professional = request.user.professional_profile
        return Response(ProfessionalSerializer(professional).data)
    except Professional.DoesNotExist:
        return Response({'error': 'No professional profile found.'}, status=status.HTTP_404_NOT_FOUND)


class SpecializationListView(generics.ListAPIView):
    serializer_class = SpecializationSerializer
    permission_classes = [AllowAny]
    queryset = Specialization.objects.all()


class LanguageListView(generics.ListAPIView):
    serializer_class = LanguageSerializer
    permission_classes = [AllowAny]
    queryset = Language.objects.all()


# ── Admin endpoints ──────────────────────────────────────────────────────────

class IsAdminUser(IsAuthenticated):
    def has_permission(self, request, view):
        return super().has_permission(request, view) and request.user.role == 'admin'


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def pending_applications(request):
    if request.user.role != 'admin':
        return Response({'error': 'Admin only.'}, status=status.HTTP_403_FORBIDDEN)
    qs = Professional.objects.filter(
        verification_status='pending'
    ).select_related('user').prefetch_related('specializations', 'languages').order_by('id')
    return Response(ProfessionalSerializer(qs, many=True).data)


@api_view(['POST'])
@permission_classes([IsAuthenticated])
def verify_professional(request, pk):
    if request.user.role != 'admin':
        return Response({'error': 'Admin only.'}, status=status.HTTP_403_FORBIDDEN)
    action = request.data.get('action')  # 'approve' or 'reject'
    if action not in ['approve', 'reject']:
        return Response({'error': 'action must be approve or reject.'}, status=status.HTTP_400_BAD_REQUEST)
    try:
        professional = Professional.objects.get(pk=pk)
    except Professional.DoesNotExist:
        return Response({'error': 'Not found.'}, status=status.HTTP_404_NOT_FOUND)

    professional.verification_status = 'verified' if action == 'approve' else 'rejected'
    professional.save()
    return Response({
        'message': f'Professional {"approved" if action == "approve" else "rejected"}.',
        'professional': ProfessionalSerializer(professional).data,
    })


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def all_professionals_admin(request):
    if request.user.role != 'admin':
        return Response({'error': 'Admin only.'}, status=status.HTTP_403_FORBIDDEN)
    status_filter = request.query_params.get('status')
    qs = Professional.objects.select_related('user').prefetch_related('specializations', 'languages')
    if status_filter:
        qs = qs.filter(verification_status=status_filter)
    return Response(ProfessionalSerializer(qs.order_by('-id'), many=True).data)
