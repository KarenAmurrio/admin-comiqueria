<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = ['cliente', 'correo', 'telefono', 'metodo_entrega', 'total', 'detalles', 'metodo_pago', 'comprobante_pago', 'estado_pago', 'estado_envio'];
}
