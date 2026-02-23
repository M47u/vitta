<?php

namespace App\Console\Commands;

use App\Mail\PaymentReminder;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPaymentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:send-reminders {--hours=2 : Hours after order creation to send reminder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send payment reminders to orders with pending transfer payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = $this->option('hours');
        $this->info("🔍 Buscando pedidos con más de {$hours} horas sin comprobante...");

        // Get orders that need reminders
        $orders = Order::where('payment_method', 'transfer')
            ->whereNull('payment_confirmed_at')
            ->whereNull('payment_proof')
            ->whereNull('payment_reminder_sent_at')
            ->where('created_at', '<=', now()->subHours($hours))
            ->where('status', '!=', 'cancelled')
            ->get();

        if ($orders->isEmpty()) {
            $this->info('✅ No hay pedidos pendientes que requieran recordatorio.');
            return Command::SUCCESS;
        }

        $this->info("📧 Enviando recordatorios a {$orders->count()} pedidos...");

        $sent = 0;
        $failed = 0;

        foreach ($orders as $order) {
            try {
                $email = $order->user ? $order->user->email : $order->guest_email;
                
                if (!$email) {
                    $this->warn("⚠️  Pedido #{$order->order_number}: Sin email");
                    $failed++;
                    continue;
                }

                // Send reminder email
                Mail::to($email)->send(new PaymentReminder($order));

                // Update reminder sent timestamp
                $order->update(['payment_reminder_sent_at' => now()]);

                $this->line("✅ Recordatorio enviado: #{$order->order_number} → {$email}");
                $sent++;

            } catch (\Exception $e) {
                $this->error("❌ Error en pedido #{$order->order_number}: " . $e->getMessage());
                $failed++;
            }
        }

        $this->newLine();
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("📊 Resumen:");
        $this->info("   ✅ Enviados: {$sent}");
        if ($failed > 0) {
            $this->warn("   ❌ Fallidos: {$failed}");
        }
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        return Command::SUCCESS;
    }
}
