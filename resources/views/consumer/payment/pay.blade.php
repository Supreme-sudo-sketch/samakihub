<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pay for Order #{{ $order->id }} — AquaHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--ocean:#0a3d4f;--tide:#0d6e8a;--foam:#e8f4f8;--coral:#e85d3a;--gold:#f0a500;--fresh:#2eb87a;--muted:#6b7d8a;--border:#dde8ed}
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'DM Sans',sans-serif;background:var(--foam);color:#1a1a2e;min-height:100vh;display:flex;flex-direction:column}

        /* Nav */
        .sh-nav{background:var(--ocean);padding:0 2rem;display:flex;align-items:center;justify-content:space-between;height:64px;position:sticky;top:0;z-index:100;box-shadow:0 2px 20px rgba(0,0,0,.3)}
        .sh-logo{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:900;color:#fff;text-decoration:none}
        .sh-logo span{color:var(--gold)}
        .back-link{color:rgba(255,255,255,.7);font-size:.875rem;text-decoration:none;display:flex;align-items:center;gap:6px;padding:6px 12px;border-radius:8px;transition:.2s}
        .back-link:hover{background:rgba(255,255,255,.1);color:#fff}

        /* Page */
        .page-header{background:#fff;border-bottom:1px solid var(--border);padding:22px 2rem}
        .page-header h2{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:var(--ocean);margin-bottom:4px}
        .page-header p{color:var(--muted);font-size:.9rem}

        .wrap{max-width:900px;margin:0 auto;padding:36px 2rem 80px;flex:1}

        /* Layout */
        .layout{display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start}
        @media(max-width:720px){.layout{grid-template-columns:1fr}}

        /* Cards */
        .card{background:#fff;border:1px solid var(--border);border-radius:16px;overflow:hidden}
        .card-header{padding:18px 22px;border-bottom:1px solid var(--border);font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:700;color:var(--ocean)}
        .card-body{padding:22px}

        /* M-Pesa form */
        .mpesa-logo{display:flex;align-items:center;gap:12px;margin-bottom:22px}
        .mpesa-badge{background:linear-gradient(135deg,#00a651,#007a3d);color:#fff;font-weight:900;font-size:1.1rem;padding:10px 18px;border-radius:10px;letter-spacing:.05em}
        .mpesa-desc{font-size:.85rem;color:var(--muted);line-height:1.4}

        .form-label{display:block;font-size:.82rem;font-weight:600;color:#374151;margin-bottom:6px}
        .form-input{width:100%;border:2px solid var(--border);border-radius:10px;padding:12px 14px;font-family:'DM Sans',sans-serif;font-size:.95rem;outline:none;transition:border-color .2s;color:#1a1a2e}
        .form-input:focus{border-color:var(--tide)}
        .form-hint{font-size:.78rem;color:var(--muted);margin-top:5px}
        .form-error{font-size:.8rem;color:var(--coral);margin-top:5px;display:none}

        .pay-btn{width:100%;background:linear-gradient(135deg,#00a651,#007a3d);color:#fff;border:none;padding:15px;border-radius:12px;font-family:'DM Sans',sans-serif;font-size:1rem;font-weight:700;cursor:pointer;margin-top:16px;transition:opacity .2s;display:flex;align-items:center;justify-content:center;gap:8px}
        .pay-btn:hover:not(:disabled){opacity:.9}
        .pay-btn:disabled{opacity:.6;cursor:not-allowed}

        /* Status states */
        .status-box{display:none;border-radius:12px;padding:20px;text-align:center;margin-top:16px}
        .status-box.waiting{background:#fffbeb;border:1px solid #fde68a}
        .status-box.success{background:#dcfce7;border:1px solid #86efac}
        .status-box.failed{background:#fee2e2;border:1px solid #fca5a5}
        .status-icon{font-size:2.5rem;margin-bottom:8px}
        .status-title{font-weight:700;font-size:1rem;margin-bottom:4px}
        .status-desc{font-size:.83rem;color:var(--muted)}

        /* Spinner */
        .spinner{width:20px;height:20px;border:3px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite;display:none}
        @keyframes spin{to{transform:rotate(360deg)}}

        /* Pulse dot */
        .pulse-dot{width:10px;height:10px;border-radius:50%;background:var(--gold);animation:pulse 1.2s infinite;display:inline-block;margin-right:6px}
        @keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(.8)}}

        /* Order summary */
        .item-row{display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid var(--foam);font-size:.875rem}
        .item-row:last-child{border-bottom:none}
        .item-name{color:#1a1a2e;font-weight:500}
        .item-qty{color:var(--muted);font-size:.8rem}
        .item-price{font-weight:600;color:var(--ocean)}
        .total-row{display:flex;justify-content:space-between;padding:14px 0 0;margin-top:4px;border-top:2px solid var(--border);font-weight:700;font-size:1.05rem;color:var(--ocean)}

        /* Steps */
        .steps{display:flex;align-items:center;justify-content:center;gap:0;margin-bottom:28px}
        .step{display:flex;flex-direction:column;align-items:center;gap:4px}
        .step-dot{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;transition:.3s}
        .step-dot.done{background:var(--fresh);color:#fff}
        .step-dot.active{background:var(--gold);color:#051e28}
        .step-dot.pending{background:var(--border);color:var(--muted)}
        .step-label{font-size:.68rem;font-weight:600;color:var(--muted);white-space:nowrap}
        .step-connector{width:40px;height:2px;background:var(--border);margin-bottom:18px}
        .step-connector.done{background:var(--fresh)}

        footer{background:#051e28;color:rgba(255,255,255,.5);text-align:center;padding:24px;font-size:.85rem}
    </style>
</head>
<body>

<nav class="sh-nav">
    <a href="{{ url('/') }}" class="sh-logo">Aqua<span>Hub</span></a>
    <a href="{{ route('cart.index') }}" class="back-link">← Back to Cart</a>
</nav>

<div class="page-header">
    <h2>Complete Your Payment</h2>
    <p>Order #{{ $order->id }} · KES {{ number_format($order->total_amount) }} · Pay securely via PesaPal</p>
</div>

<div class="wrap">

    {{-- Progress steps --}}
    <div class="steps">
        <div class="step">
            <div class="step-dot done">✓</div>
            <div class="step-label">Cart</div>
        </div>
        <div class="step-connector done"></div>
        <div class="step">
            <div class="step-dot done">✓</div>
            <div class="step-label">Delivery</div>
        </div>
        <div class="step-connector done"></div>
        <div class="step">
            <div class="step-dot active">3</div>
            <div class="step-label">Payment</div>
        </div>
        <div class="step-connector"></div>
        <div class="step">
            <div class="step-dot pending">4</div>
            <div class="step-label">Confirmed</div>
        </div>
    </div>

    <div class="layout">
        {{-- Payment form --}}
        <div class="card">
            <div class="card-header">💳 Pay via PesaPal</div>
            <div class="card-body">

                <div class="mpesa-logo">
                    <div class="mpesa-badge" style="background:linear-gradient(135deg,#1a74e4,#0d47a1)">PESAPAL</div>
                    <div class="mpesa-desc">Pay securely using PesaPal. You will be redirected to complete payment with your preferred method (M-Pesa, card, bank transfer, etc.).</div>
                </div>

                <div id="payment-form">
                    <div style="margin-bottom:16px">
                        <label class="form-label" for="phone">Phone Number</label>
                        <input
                            type="tel"
                            id="phone"
                            class="form-input"
                            placeholder="e.g. 0712 345 678"
                            value="{{ auth()->user()->phone ?? '' }}"
                            maxlength="15"
                        >
                        <div class="form-hint">Enter your phone number for payment notifications.</div>
                        <div class="form-error" id="phone-error"></div>
                    </div>

                    <div style="background:var(--foam);border-radius:10px;padding:14px;font-size:.82rem;color:var(--muted);line-height:1.6;margin-bottom:4px">
                        <strong style="color:var(--ocean)">How it works:</strong><br>
                        1. Enter your phone number above.<br>
                        2. Click "Proceed to Payment".<br>
                        3. You'll be redirected to PesaPal.<br>
                        4. Pay using M-Pesa, card, or other methods.<br>
                        5. You'll return automatically after payment.
                    </div>

                    <button class="pay-btn" id="pay-btn" onclick="initiatePayment()" style="background:linear-gradient(135deg,#1a74e4,#0d47a1)">
                        <span id="btn-label">Proceed to Payment — KES {{ number_format($order->total_amount) }}</span>
                        <div class="spinner" id="btn-spinner"></div>
                    </button>
                </div>

                {{-- Processing --}}
                <div class="status-box waiting" id="status-waiting">
                    <div class="status-icon">🔄</div>
                    <div class="status-title"><span class="pulse-dot"></span>Redirecting to PesaPal...</div>
                    <div class="status-desc">Please wait while we redirect you to complete your payment securely.</div>
                </div>

                {{-- Success --}}
                <div class="status-box success" id="status-success">
                    <div class="status-icon">✅</div>
                    <div class="status-title" style="color:#166534">Payment Successful!</div>
                    <div class="status-desc">Your order has been confirmed. Redirecting you to order tracking...</div>
                </div>

                {{-- Failed --}}
                <div class="status-box failed" id="status-failed">
                    <div class="status-icon">❌</div>
                    <div class="status-title" style="color:#991b1b">Payment Failed</div>
                    <div class="status-desc" id="fail-desc">The payment was cancelled. Please try again.</div>
                    <button onclick="resetForm()" style="margin-top:14px;background:var(--coral);color:#fff;border:none;padding:9px 20px;border-radius:8px;font-size:.85rem;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif">Try Again</button>
                </div>

            </div>
        </div>

        {{-- Order summary --}}
        <div>
            <div class="card">
                <div class="card-header">📦 Order Summary</div>
                <div class="card-body">
                    @foreach($order->items as $item)
                        <div class="item-row">
                            <div>
                                <div class="item-name">{{ $item->product->name ?? 'Product' }}</div>
                                <div class="item-qty">{{ $item->quantity_kg }} kg × KES {{ number_format($item->unit_price) }}</div>
                            </div>
                            <div class="item-price">KES {{ number_format($item->subtotal) }}</div>
                        </div>
                    @endforeach

                    <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border)">
                        <div style="display:flex;justify-content:space-between;font-size:.85rem;color:var(--muted);margin-bottom:6px">
                            <span>Subtotal</span>
                            <span>KES {{ number_format($order->total_amount - $order->delivery_fee) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;font-size:.85rem;color:var(--muted);margin-bottom:6px">
                            <span>Delivery fee</span>
                            <span>KES {{ number_format($order->delivery_fee) }}</span>
                        </div>
                        <div class="total-row">
                            <span>Total</span>
                            <span>KES {{ number_format($order->total_amount) }}</span>
                        </div>
                    </div>

                    <div style="margin-top:16px;padding:12px;background:var(--foam);border-radius:10px;font-size:.8rem;color:var(--muted)">
                        <strong style="color:var(--ocean)">Delivery to:</strong><br>
                        {{ $order->delivery_address }}
                        @if($order->delivery_notes)
                            <br><em>Note: {{ $order->delivery_notes }}</em>
                        @endif
                    </div>
                </div>
            </div>

            <div style="margin-top:14px;padding:14px 16px;background:#fff;border:1px solid var(--border);border-radius:12px;font-size:.78rem;color:var(--muted);line-height:1.5">
                🔒 Your payment is processed securely by <strong style="color:#1a74e4">PesaPal</strong>. AquaHub does not store your payment details.
            </div>
        </div>
    </div>
</div>

<footer>© 2026 <span style="color:#f0a500">AquaHub</span> — Fresh fish, delivered fresh.</footer>

<script>
    const ORDER_ID = {{ $order->id }};
    const STATUS_URL = '{{ route('payment.status', $order->id) }}';
    const INITIATE_URL = '{{ route('payment.initiate', $order->id) }}';
    const ORDER_URL = '{{ route('orders.show', $order->id) }}';
    const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let pollInterval = null;
    let pollCount = 0;

    function showState(state) {
        document.getElementById('payment-form').style.display  = state === 'form'    ? 'block' : 'none';
        document.getElementById('status-waiting').style.display = state === 'waiting' ? 'flex' : 'none';
        document.getElementById('status-success').style.display = state === 'success' ? 'flex' : 'none';
        document.getElementById('status-failed').style.display  = state === 'failed'  ? 'flex' : 'none';

        ['status-waiting','status-success','status-failed'].forEach(id => {
            const el = document.getElementById(id);
            if (el.style.display === 'flex') {
                el.style.flexDirection = 'column';
                el.style.alignItems = 'center';
            }
        });
    }

    function resetForm() {
        stopPolling();
        showState('form');
        document.getElementById('pay-btn').disabled = false;
        document.getElementById('btn-label').textContent = 'Proceed to Payment — KES {{ number_format($order->total_amount) }}';
        document.getElementById('btn-spinner').style.display = 'none';
        document.getElementById('phone-error').style.display = 'none';
    }

    function stopPolling() {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
            pollCount = 0;
        }
    }

    function startPolling() {
        stopPolling();
        pollCount = 0;
        pollInterval = setInterval(async () => {
            pollCount++;
            try {
                const res = await fetch(STATUS_URL, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();

                if (data.payment_status === 'paid') {
                    stopPolling();
                    showState('success');
                    setTimeout(() => { window.location.href = ORDER_URL; }, 2500);
                } else if (data.payment_status === 'failed') {
                    stopPolling();
                    document.getElementById('fail-desc').textContent = 'Payment was cancelled. Please try again.';
                    showState('failed');
                }
            } catch (e) {
                // Network error — keep polling
            }
        }, 5000);

        // Stop polling after 10 minutes (timeout)
        setTimeout(() => {
            stopPolling();
            const waitBox = document.getElementById('status-waiting');
            if (waitBox.style.display !== 'none') {
                document.getElementById('fail-desc').textContent = 'Payment verification timed out. Please try again or contact support.';
                showState('failed');
            }
        }, 600000);
    }

    async function initiatePayment() {
        const phone = document.getElementById('phone').value.trim();
        const errEl = document.getElementById('phone-error');
        errEl.style.display = 'none';

        if (!phone) {
            errEl.textContent = 'Please enter your phone number.';
            errEl.style.display = 'block';
            return;
        }

        const btn = document.getElementById('pay-btn');
        btn.disabled = true;
        document.getElementById('btn-label').textContent = 'Processing...';
        document.getElementById('btn-spinner').style.display = 'block';

        try {
            const res = await fetch(INITIATE_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ phone }),
            });

            const data = await res.json();

            if (data.success && data.paid) {
                // Already paid
                showState('success');
                setTimeout(() => { window.location.href = ORDER_URL; }, 2000);
                return;
            }

            if (data.success && data.redirect_url) {
                // Show processing state and redirect to PesaPal
                showState('waiting');
                startPolling();
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 1500);
            } else {
                errEl.textContent = data.error ?? 'Failed to initiate payment. Try again.';
                errEl.style.display = 'block';
                btn.disabled = false;
                document.getElementById('btn-label').textContent = 'Proceed to Payment — KES {{ number_format($order->total_amount) }}';
                document.getElementById('btn-spinner').style.display = 'none';
            }
        } catch (e) {
            errEl.textContent = 'Network error. Please check your connection and try again.';
            errEl.style.display = 'block';
            btn.disabled = false;
            document.getElementById('btn-label').textContent = 'Proceed to Payment — KES {{ number_format($order->total_amount) }}';
            document.getElementById('btn-spinner').style.display = 'none';
        }
    }

    // Allow Enter key on phone input
    document.getElementById('phone').addEventListener('keydown', e => {
        if (e.key === 'Enter') initiatePayment();
    });

    showState('form');
</script>

@include('partials.samakibot')
</body>
</html>
