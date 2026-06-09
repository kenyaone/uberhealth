<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SessionFeedback extends Model {
    protected $table = 'session_feedback';
    protected $fillable = ['consultation_id','user_id','overall_rating','communication_rating','felt_heard','would_recommend','felt_safe','comment'];
    protected $casts = ['felt_heard'=>'boolean','would_recommend'=>'boolean','felt_safe'=>'boolean'];
}
