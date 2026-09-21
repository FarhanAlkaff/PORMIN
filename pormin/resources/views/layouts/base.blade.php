<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PORMIN') · Al-Azhar Cairo Palembang</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700&family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root{
            --azhar-green:#0f5f3a;
            --azhar-green-dark:#0a3d25;
            --azhar-gold:#c9a24b;
            --azhar-cream:#faf6ec;
            --azhar-ink:#1a2a22;
        }
        html,body{font-family:'Instrument Sans',system-ui,sans-serif;color:var(--azhar-ink);background:var(--azhar-cream);}
        h1,h2,h3,.display-1,.display-2,.display-3,.display-4,.display-5,.display-6,.hero-title{font-family:'Fraunces',Georgia,serif;letter-spacing:-.01em;}
        a{color:var(--azhar-green);}
        .navbar-azhar{background:linear-gradient(90deg,var(--azhar-green-dark),var(--azhar-green));}
        .navbar-azhar .navbar-brand,.navbar-azhar .nav-link{color:#fff !important;}
        .navbar-azhar .nav-link:hover{color:var(--azhar-gold) !important;}
        .brand-mark{width:44px;height:44px;background:var(--azhar-gold);color:var(--azhar-green-dark);border-radius:10px;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-family:'Fraunces',serif;font-size:1.35rem;}
        .btn-azhar{background:var(--azhar-green);color:#fff;border:none;font-weight:600;}
        .btn-azhar:hover{background:var(--azhar-green-dark);color:#fff;}
        .btn-gold{background:var(--azhar-gold);color:var(--azhar-green-dark);border:none;font-weight:700;}
        .btn-gold:hover{background:#b3892d;color:#fff;}
        .hero{background:linear-gradient(135deg,var(--azhar-green-dark) 0%,var(--azhar-green) 55%,#146b44 100%);color:#fff;position:relative;overflow:hidden;}
        .hero::after{content:"";position:absolute;inset:0;background-image:radial-gradient(circle at 15% 20%, rgba(201,162,75,.18), transparent 40%),radial-gradient(circle at 85% 80%, rgba(255,255,255,.08), transparent 40%);}
        .hero-inner{position:relative;z-index:2;}
        .grade-card{border:1px solid #e6dfc7;border-radius:16px;background:#fff;transition:all .2s ease;height:100%;}
        .grade-card:hover{transform:translateY(-4px);box-shadow:0 12px 28px rgba(15,95,58,.15);border-color:var(--azhar-gold);}
        .grade-badge{width:56px;height:56px;border-radius:14px;background:var(--azhar-cream);color:var(--azhar-green-dark);display:flex;align-items:center;justify-content:center;font-weight:700;font-family:'Fraunces',serif;font-size:1.15rem;border:1px solid var(--azhar-gold);}
        .section-title{font-family:'Fraunces',serif;font-weight:700;letter-spacing:-.01em;}
        .footer{background:var(--azhar-green-dark);color:#e9e6d6;}
        .form-label{font-weight:600;color:var(--azhar-green-dark);}
        .required::after{content:" *";color:#c0392b;}
        .status-pill{padding:.3rem .7rem;border-radius:999px;font-size:.78rem;font-weight:600;letter-spacing:.02em;}
        .status-terdaftar{background:#e7f4ed;color:#0f5f3a;}
        .status-menunggu{background:#fff3d6;color:#8a6100;}
        .status-lolos{background:#e0edff;color:#0b4c9a;}
        .status-layak{background:#e5f6e9;color:#0a6b2e;}
        .status-dipanggil{background:#ecdcff;color:#5a1aa8;}
        .status-diterima{background:#0f5f3a;color:#fff;}
        .status-ditolak,.status-tidak{background:#fde2e2;color:#a11a1a;}
        .sidebar{background:#0a3d25;min-height:100vh;color:#e9e6d6;}
        .sidebar a{color:#e9e6d6;text-decoration:none;display:block;padding:.7rem 1rem;border-radius:8px;}
        .sidebar a:hover,.sidebar a.active{background:rgba(201,162,75,.18);color:#fff;}
        .kpi-card{background:#fff;border-radius:14px;border:1px solid #e6dfc7;padding:1.25rem;}
        .kpi-card .kpi-num{font-family:'Fraunces',serif;font-size:2.1rem;font-weight:700;color:var(--azhar-green-dark);}
        .timeline{border-left:2px solid var(--azhar-gold);padding-left:1rem;}
        .timeline-item{position:relative;padding-bottom:.9rem;}
        .timeline-item::before{content:"";position:absolute;left:-1.4rem;top:.35rem;width:.8rem;height:.8rem;background:var(--azhar-gold);border-radius:50%;}
        .table-vcenter td,.table-vcenter th{vertical-align:middle;}
    </style>
    @stack('head')
</head>
<body>
@yield('body')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    window._token = document.querySelector('meta[name=csrf-token]').content;
</script>
@stack('scripts')
</body>
</html>
