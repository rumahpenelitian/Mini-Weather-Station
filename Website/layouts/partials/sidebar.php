<?php
// Tentukan base URL untuk assets
$baseUrl = 'https://rptugas.site/assets/';
?>

<style>
.menu:not(.menu-no-animation) .menu-link > :not(.menu-icon) {
  color: white;
}
</style>
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
      <a href="index.php" class="app-brand-link">
        <span class="app-brand-logo demo">  
          <img src="<?php echo $baseUrl; ?>/img/logo (2).png" style="max-width: 20%">
        </span>
      </a>

      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <i class="bx bx-chevron-left bx-sm align-middle"></i>
      </a>
    </div>


    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1" style="    background: linear-gradient(90deg, #1D2E69 0%, #395BCF 100%);">
        <!-- Dashboards -->
        <li class="menu-item">  
            <a href="index.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div class="text-truncate" data-i18n="Weather Station">Weather Station</div>
            </a>
        </li>
        <li class="menu-item">  
            <a href="index2.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div class="text-truncate" data-i18n="Monitoring Energi Panel Surya">Monitoring Energi Panel Surya</div>
            </a>
        </li>
        <li class="menu-item">  
            <a href="index3.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div class="text-truncate" data-i18n="Monitoring Tip Bucket">Monitoring Tip Bucket</div>
            </a>
        </li>
        <li class="menu-item">  
            <a href="index5.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div class="text-truncate" data-i18n="Status Motor Driver & LDR">Status Motor Driver & LDR</div>
            </a>
        </li>
        <li class="menu-item">  
            <a href="index4.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div class="text-truncate" data-i18n="Monitoring Wind weather">Monitoring Wind weather</div>
            </a>
        </li>
        
    </ul>
    
  </aside>

