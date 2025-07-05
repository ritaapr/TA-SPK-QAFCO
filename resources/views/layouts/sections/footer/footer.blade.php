@php
$containerFooter = !empty($containerNav) ? $containerNav : 'container-fluid';
@endphp

<!-- Footer-->
<footer class="content-footer footer bg-footer-theme">
  <div class="{{ $containerFooter }}">
    <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
     <div class="text-body">
  © <script>document.write(new Date().getFullYear())</script>, made with ❤️ by 
  <a href="https://github.com/ritaapr/TA-SPK-QAFCO.git" target="_blank">Rita Aprilia</a>
</div>

      
    </div>
  </div>
</footer>
<!--/ Footer-->
