<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComicController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Models\Pedido;
use Illuminate\Support\Facades\Mail;
// Helper to decrement stock based on order details
function descontarStock($detalles) {
    $items = explode(' | ', $detalles);
    foreach ($items as $item) {
        $parts = explode('x ', $item, 2);
        if (count($parts) === 2) {
            $cantidad = intval($parts[0]);
            $titulo = trim($parts[1]);
            $comic = \App\Models\Comic::where('titulo', $titulo)->first();
            if ($comic) {
                $comic->stock = max(0, $comic->stock - $cantidad);
                $comic->save();
            }
        }
    }
}

// Helper to send customer emails automatically
function enviarCorreoPedido($pedido, $tipo) {
    $idFormateado = str_pad($pedido->id, 4, '0', STR_PAD_LEFT);
    $subject = "";
    $tituloSeccion = "";
    $mensajeCuerpo = "";
    
    if ($tipo === 'creado') {
        $subject = "Pedido Recibido #{$idFormateado} - Comiquería Aguilar";
        $tituloSeccion = "Hemos recibido tu pedido";
        if ($pedido->metodo_pago === 'qr') {
            $mensajeCuerpo = "Tu pedido se ha registrado correctamente. Estamos en espera de validar tu comprobante de pago QR. Te notificaremos por correo una vez sea confirmado.";
        } else {
            $mensajeCuerpo = "Tu pago mediante PayPal ha sido procesado con éxito. Ya estamos preparando tu orden para ser despachada.";
        }
    } elseif ($tipo === 'confirmado') {
        $subject = "Pago Confirmado #{$idFormateado} - Comiquería Aguilar";
        $tituloSeccion = "Tu pago ha sido verificado";
        $mensajeCuerpo = "¡Buenas noticias! Hemos confirmado la validez de tu comprobante de pago. Tu pedido se encuentra en preparación para ser entregado o enviado.";
    } elseif ($tipo === 'entregado') {
        $subject = "Pedido Completado #{$idFormateado} - Comiquería Aguilar";
        $tituloSeccion = "Tu pedido ha sido despachado";
        $mensajeCuerpo = "Queremos informarte que tu pedido #{$idFormateado} ha sido marcado como Completado (Enviado por Terminal o Recogido de nuestra sucursal). ¡Esperamos que disfrutes de tu lectura!";
    }

    $html = "
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden;'>
        <div style='background-color: #8b5cf6; padding: 24px; text-align: center; color: white;'>
            <h1 style='margin: 0; font-size: 24px;'>⚡ Comiquería Aguilar</h1>
            <p style='margin: 4px 0 0 0; font-size: 14px; opacity: 0.9;'>Tu portal al multiverso</p>
        </div>
        <div style='padding: 24px; background-color: #ffffff;'>
            <h2 style='color: #1f2937; margin-top: 0;'>{$tituloSeccion}</h2>
            <p style='color: #4b5563; line-height: 1.6; font-size: 14px;'>Hola <strong>{$pedido->cliente}</strong>,</p>
            <p style='color: #4b5563; line-height: 1.6; font-size: 14px;'>{$mensajeCuerpo}</p>
            
            <div style='background-color: #f9fafb; border: 1px solid #f3f4f6; border-radius: 8px; padding: 16px; margin: 20px 0;'>
                <h3 style='margin-top: 0; color: #374151; font-size: 15px; border-bottom: 1px solid #e5e7eb; padding-bottom: 8px;'>Resumen del Pedido #{$idFormateado}</h3>
                <table style='width: 100%; border-collapse: collapse; font-size: 13px;'>
                    <tr>
                        <td style='padding: 4px 0; color: #6b7280;'>Cliente:</td>
                        <td style='padding: 4px 0; text-align: right; color: #111827; font-weight: bold;'>{$pedido->cliente}</td>
                    </tr>
                    <tr>
                        <td style='padding: 4px 0; color: #6b7280;'>Contacto:</td>
                        <td style='padding: 4px 0; text-align: right; color: #111827;'>{$pedido->telefono}</td>
                    </tr>
                    <tr>
                        <td style='padding: 4px 0; color: #6b7280;'>Entrega:</td>
                        <td style='padding: 4px 0; text-align: right; color: #111827;'>{$pedido->metodo_entrega}</td>
                    </tr>
                    <tr>
                        <td style='padding: 4px 0; color: #6b7280;'>Método Pago:</td>
                        <td style='padding: 4px 0; text-align: right; color: #111827; text-transform: uppercase;'>{$pedido->metodo_pago}</td>
                    </tr>
                    <tr>
                        <td style='padding: 12px 0 4px 0; color: #6b7280; border-top: 1px dashed #e5e7eb;'>Detalles:</td>
                        <td style='padding: 12px 0 4px 0; text-align: right; color: #111827; border-top: 1px dashed #e5e7eb;'>{$pedido->detalles}</td>
                    </tr>
                    <tr>
                        <td style='padding: 12px 0 0 0; color: #111827; font-weight: bold; font-size: 15px; border-top: 1px solid #e5e7eb;'>Total:</td>
                        <td style='padding: 12px 0 0 0; text-align: right; color: #8b5cf6; font-weight: bold; font-size: 16px; border-top: 1px solid #e5e7eb;'>Bs. " . number_format($pedido->total, 2) . "</td>
                    </tr>
                </table>
            </div>
            
            <p style='color: #9ca3af; font-size: 12px; text-align: center; margin-top: 30px; border-top: 1px solid #f3f4f6; padding-top: 15px;'>
                Este es un correo automático. Por favor, no respondas a este mensaje.
            </p>
        </div>
    </div>";

    try {
        Mail::html($html, function($message) use ($pedido, $subject) {
            $message->to($pedido->correo)
                    ->subject($subject);
        });
    } catch (\Exception $e) {
        \Log::error("Error al enviar correo del pedido #{$pedido->id}: " . $e->getMessage());
    }
}


Route::get('/', function () {
    return redirect('/login');
});

Route::get('/test-mail', function() {
    try {
        \Illuminate\Support\Facades\Mail::html('<h1>Prueba de Correo desde Railway</h1>', function($message) {
            $message->to('karenamurriohuaygua@gmail.com')
                    ->subject('Prueba de Correo en Producción');
        });
        return "Correo enviado con éxito.";
    } catch (\Exception $e) {
        return "Error al enviar correo: " . $e->getMessage();
    }
});

// API Routes for Astro (public, no auth required)
Route::get('/api/comics', function () {
    return response()->json(\App\Models\Comic::all());
});

Route::post('/api/pedidos', function(\Illuminate\Http\Request $request) {
    $data = $request->except('comprobante');
    
    if ($request->hasFile('comprobante')) {
        $file = $request->file('comprobante');
        $path = $file->store('comprobantes', 'public');
        $data['comprobante_pago'] = '/storage/' . $path;
    }
    
    if (($data['metodo_pago'] ?? 'paypal') === 'qr') {
        $data['estado_pago'] = 'pendiente';
    } else {
        $data['estado_pago'] = 'completado';
    }
    $data['estado_envio'] = 'pendiente';

    $pedido = Pedido::create($data);

    if ($pedido->estado_pago === 'completado') {
        descontarStock($pedido->detalles);
    }

    enviarCorreoPedido($pedido, 'creado');

    return response()->json(['success' => true, 'pedido' => $pedido]);
});

// Authentication Routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Logout (authenticated only)
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Admin Panel Routes (authenticated only)
Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Comics Routes
    Route::get('/admin/comics', [ComicController::class, 'index'])->name('admin.comics.index');
    Route::get('/admin/comics/create', [ComicController::class, 'create'])->name('admin.comics.create');
    Route::post('/admin/guardar', [ComicController::class, 'store'])->name('comics.store');
    Route::get('/admin/editar/{id}', [ComicController::class, 'edit'])->name('comics.edit');
    Route::put('/admin/actualizar/{id}', [ComicController::class, 'update'])->name('comics.update');
    Route::delete('/admin/eliminar/{id}', [ComicController::class, 'destroy'])->name('comics.destroy');

    // Pedidos Routes
    Route::get('/admin/pedidos', function() {
        $pedidos = Pedido::orderBy('created_at', 'desc')->get();
        return view('admin_pedidos', compact('pedidos'));
    })->name('admin.pedidos');

    Route::post('/admin/pedidos/{id}/estado', function(\Illuminate\Http\Request $request, $id) {
        $pedido = Pedido::findOrFail($id);
        $request->validate([
            'estado_pago' => 'required|in:pendiente,aprobado,rechazado,completado'
        ]);
        $nuevoEstado = $request->input('estado_pago');
        $anteriorEstado = $pedido->estado_pago;

        $pedido->estado_pago = $nuevoEstado;
        $pedido->save();

        if (in_array($nuevoEstado, ['aprobado', 'completado']) && !in_array($anteriorEstado, ['aprobado', 'completado'])) {
            descontarStock($pedido->detalles);
            enviarCorreoPedido($pedido, 'confirmado');
        }

        return back()->with('success', 'Estado del pago actualizado correctamente.');
    })->name('admin.pedidos.actualizar_estado');

    Route::post('/admin/pedidos/{id}/envio', function(\Illuminate\Http\Request $request, $id) {
        $pedido = Pedido::findOrFail($id);
        $request->validate([
            'estado_envio' => 'required|in:pendiente,completado'
        ]);
        $pedido->estado_envio = $request->input('estado_envio');
        $pedido->save();

        if ($pedido->estado_envio === 'completado') {
            enviarCorreoPedido($pedido, 'entregado');
        }

        return back()->with('success', 'Estado de la entrega del pedido actualizado.');
    })->name('admin.pedidos.actualizar_envio');
});