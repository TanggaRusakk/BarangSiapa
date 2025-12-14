<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<button id="pay-button">Bayar Sekarang</button>

<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){
    // Panggil Midtrans Snap Pop-up dengan Snap Token
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                alert("Pembayaran berhasil!"); console.log(result);
            },
            onPending: function(result){
                alert("Pembayaran tertunda!"); console.log(result);
            },
            onError: function(result){
                alert("Pembayaran gagal!"); console.log(result);
            },
            onClose: function(){
                alert('Anda menutup popup tanpa menyelesaikan pembayaran');
            }
        });
    };
</script>