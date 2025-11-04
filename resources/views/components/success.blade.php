   @session('success')
   <div class="flex justify-end items-end">
  <p id="success-message" class="bg-green-400 text-white p-3 rounded-xl justify-end items-end">
            {{ $value }}
        </p>
   </div>
      

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const successMessage = document.getElementById('success-message');

                if (successMessage) {

                    setTimeout(function() {
                        successMessage.style.display = 'none';
                    }, 2000);
                }
            });
        </script>
    @endsession