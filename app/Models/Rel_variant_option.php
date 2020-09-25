<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rel_variant_option extends Model
{
	protected $table = 'rel_variant_option';
	protected $primaryKey = "variant_option_id";
    public $timestamps = false;
}