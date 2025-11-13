<?php

namespace App\Models;

use App\Traits\CommonQueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    //Include the CommonQueryScopes trait for reusable query scopes (title search, status filter))
    use CommonQueryScopes;
    
    protected $fillable = ['title', 'description', 'status', 'due_date', 'project_id', 'assigned_to'];

    public function project() { 
        return $this->belongsTo(Project::class); 
    }
    
    public function user() { 
        return $this->belongsTo(User::class, 'assigned_to'); 
    }
    
    public function comments() { 
        return $this->hasMany(Comment::class); 
    }
}