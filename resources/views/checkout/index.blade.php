@extends('layouts.app')
@section('title', 'Checkout')
@section('content')
    <h1>Checkout</h1>
    <div class="checkout-layout">
        <div class="card">
            <form method="POST" action="{{ route('checkout.store') }}">
                @csrf
                <input type="hidden" name="checkout_token" value="{{ $checkoutToken }}">
                <label>Nama Penerima<input class="input" name="recipient_name" value="{{ old('recipient_name', auth()->user()->name) }}" required>
                </label>
                <label>No. Telepon<input class="input" name="phone" value="{{ old('phone') }}" required>
                </label>
                <label>Alamat<textarea class="input" rows="4" name="address" required>{{ old('address') }}</textarea>
                </label>
                <label>Ekspedisi<select name="shipping_service_id" required>
                        <option value="">Pilih ekspedisi</option>
                        @foreach($shippingServices as $service)
                        <option value="{{ $service->id }}" data-cost="{{ $service->cost }}"
                        @selected(old('shipping_service_id') == $service->id)>{{ $service->courier }} - {{ $service->service }} ({{ $service->eta }}) - Rp{{ number_format($service->cost, 2, ',', '.') 
                        }}</option>
                        @endforeach
                    </select>
                </label>
                <button class="btn" @disabled($shippingServices->isEmpty())>Buat Pesanan</button>
                @if(
                        $shippingServices
                        -> isEmpty()
                    )
                    <p class="validation-error">Belum ada layanan pengiriman aktif. Hubungi admin sebelum
                checkout.</p>
                @endif
            </form>
        </div>
        <div class="card">
            <h3>Ringkasan</h3>
            @foreach($items as $item)
                <p>{{ $item['product']->name }} x {{ $item['quantity'] }} = Rp{{ 
            number_format($item['lineTotal'], 2, ',', '.') }}</p>
            @endforeach
            <hr>
            <div data-subtotal="{{ $subtotal }}"><strong>Subtotal Rp{{ number_format($subtotal, 2, ',', '.') }}</strong></div>
            <div class="summary-line"><span >Ongkos kirim</span><strong id="shipping-total" data-shipping-total>Pilih ekspedisi</strong></div>
            <div class="summary-total" id="order-total" data-order-total></div>
            <p class="muted">Ongkir ditambahkan sesuai ekspedisi yang dipilih.</p>
        </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectEkspedisi = document.getElementById('shipping-select');
        const shippingTotalText = document.getElementById('shipping-total');
        const orderTotalText = document.getElementById('order-total');
        
        // Ambil subtotal langsung dari atribut data-subtotal yang sudah kamu buat di baris 31
        const subtotalElement = document.querySelector('[data-subtotal]');
        const subtotal = parseFloat(subtotalElement.getAttribute('data-subtotal')) || 0;

        const formatRupiah = (angka) => {
            return 'Rp ' + parseFloat(angka).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        };

        function hitungTotal() {
            if (!selectEkspedisi.value) {
                shippingTotalText.innerText = 'Pilih ekspedisi';
                orderTotalText.innerText = '';
                return;
            }

            const selectedOption = selectEkspedisi.options[selectEkspedisi.selectedIndex];
            const ongkir = parseFloat(selectedOption.getAttribute('data-cost')) || 0;
            const total = subtotal + ongkir;

            shippingTotalText.innerText = formatRupiah(ongkir);
            orderTotalText.innerHTML = `<strong>Total: ${formatRupiah(total)}</strong>`;
        }

        // Panggil saat pertama kali load, dan saat dropdown diganti
        hitungTotal();
        selectEkspedisi.addEventListener('change', hitungTotal);
    });
</script>

@endsection