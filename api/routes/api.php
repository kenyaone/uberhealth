<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\CorporateController;
use App\Http\Controllers\CrisisController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PHRController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

// -------------------------------------------------------
// Public routes (no auth required)
// -------------------------------------------------------

Route::post('/auth/signup', [AuthController::class, 'signup']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/refresh', [AuthController::class, 'refresh']);

Route::get('/crisis/hotlines', [CrisisController::class, 'hotlines']);

Route::get('/subscriptions/plans', [SubscriptionController::class, 'plans']);

Route::get('/corporate/tiers', [CorporateController::class, 'tiers']);

// M-Pesa callbacks (called by Safaricom, no token)
Route::post('/payments/callback', [PaymentController::class, 'callback']);
Route::post('/payments/subscription/callback', [PaymentController::class, 'subscriptionCallback']);
Route::post('/payments/b2c/result', [PaymentController::class, 'b2cResult']);
Route::post('/payments/b2c/timeout', [PaymentController::class, 'b2cTimeout']);

// Assessment questions — public so TakeAssessment page works before submitting
Route::get('/assessments/questions/{type}', [AssessmentController::class, 'questions']);

// Professional routes — specific routes must appear before {id} wildcard
Route::get('/professionals', [ProfessionalController::class, 'index']);
Route::post('/professionals/register', [ProfessionalController::class, 'register'])->middleware('auth:api');
Route::get('/professionals/me/dashboard', [ProfessionalController::class, 'dashboard'])->middleware('auth:api');
Route::put('/professionals/availability', [ProfessionalController::class, 'updateAvailability'])->middleware('auth:api');
Route::get('/professionals/{id}', [ProfessionalController::class, 'show']);

// -------------------------------------------------------
// Authenticated routes
// -------------------------------------------------------

Route::middleware('auth:api')->group(function () {

    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::put('/auth/me', [AuthController::class, 'updateProfile']);

    // Assessments
    Route::get('/assessments', [AssessmentController::class, 'index']);
    Route::post('/assessments', [AssessmentController::class, 'store']);
    Route::get('/assessments/recommend', [AssessmentController::class, 'recommend']);
    Route::get('/assessments/{id}', [AssessmentController::class, 'show']);

    // Professionals (authenticated actions)
    Route::post('/professionals/apply', [ProfessionalController::class, 'apply']);

    // Consultations — specific routes BEFORE the {id} wildcard
    Route::get('/consultations', [ConsultationController::class, 'index']);
    Route::post('/consultations', [ConsultationController::class, 'store']);
    Route::get('/consultations/professional/list', [ConsultationController::class, 'proList']);
    Route::get('/consultations/{id}', [ConsultationController::class, 'show']);
    Route::post('/consultations/{id}/join', [ConsultationController::class, 'join']);
    Route::post('/consultations/{id}/end', [ConsultationController::class, 'endSession']);
    Route::post('/consultations/{id}/cancel', [ConsultationController::class, 'cancel']);
    Route::post('/consultations/{id}/rate', [ConsultationController::class, 'rate']);
    Route::put('/consultations/{id}/notes', [ConsultationController::class, 'addNotes']);
    Route::post('/consultations/{id}/recording', [ConsultationController::class, 'saveRecording']);
    Route::delete('/consultations/{id}/recording', [ConsultationController::class, 'deleteRecording']);
    Route::get('/consultations/{id}/recording/share', [ConsultationController::class, 'shareRecording']);
    Route::post('/consultations/{id}/notes-request', [ConsultationController::class, 'requestNotes']);
    Route::post('/consultations/{id}/follow-up', [ConsultationController::class, 'bookFollowUp']);

    // PHR — Mood
    Route::get('/phr/mood/stats', [PHRController::class, 'moodStats']);
    Route::get('/phr/mood', [PHRController::class, 'moodIndex']);
    Route::post('/phr/mood', [PHRController::class, 'moodStore']);

    // PHR — Cravings
    Route::get('/phr/cravings', [PHRController::class, 'cravingIndex']);
    Route::post('/phr/cravings', [PHRController::class, 'cravingStore']);

    // PHR — Sobriety
    Route::get('/phr/sobriety', [PHRController::class, 'sobrietyIndex']);
    Route::post('/phr/sobriety', [PHRController::class, 'sobrietyStore']);
    Route::post('/phr/sobriety/{id}/relapse', [PHRController::class, 'sobrietyRelapse']);

    // Payments (authenticated initiation)
    Route::post('/payments/initiate', [PaymentController::class, 'initiate']);
    Route::post('/payments/subscription/initiate', [PaymentController::class, 'subscriptionInitiate']);

    // Crisis
    Route::post('/crisis/report', [CrisisController::class, 'report']);

    // Subscriptions
    Route::post('/subscriptions/subscribe', [SubscriptionController::class, 'subscribe']);
    Route::get('/subscriptions/current', [SubscriptionController::class, 'current']);

    // Corporate / EAP
    Route::post('/corporate/apply', [CorporateController::class, 'apply']);

    // Lessons
    Route::get('/lessons', [LessonController::class, 'index']);
    Route::get('/lessons/{slug}', [LessonController::class, 'show']);
    Route::post('/lessons/{id}/progress', [LessonController::class, 'progress']);

    // AI
    Route::post('/ai/chat', [AiController::class, 'chat']);
    Route::post('/ai/assessment-insight', [AiController::class, 'assessmentInsight']);
    Route::post('/ai/match-explain', [AiController::class, 'matchExplain']);
    Route::get('/ai/progress-insight', [AiController::class, 'progressInsight']);
    Route::post('/ai/soap-notes', [AiController::class, 'soapNotes']);

    // Admin routes (role check via middleware)
    Route::middleware('can:admin')->prefix('admin')->group(function () {
        Route::get('/stats', [AdminController::class, 'stats']);
        Route::get('/professionals', [AdminController::class, 'professionals']);
        Route::put('/professionals/{id}/verify', [AdminController::class, 'verifyProfessional']);
        Route::get('/consultations', [AdminController::class, 'consultations']);
        Route::put('/consultations/{id}/confirm', [AdminController::class, 'confirmConsultation']);
    });
});
