<?php
/**
 * Created by PhpStorm.
 * User: anasrulysf
 * Date: 8/9/2022
 * Time: 9:07 AM
 */

namespace App\Models;
use CodeIgniter\Model;
class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = ['name', 'email'];
}