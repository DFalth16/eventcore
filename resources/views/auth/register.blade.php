<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>EventCore · Registro</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@200;300;400;500;600&family=JetBrains+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root{--c0:#04080f;--c1:#080e1a;--c2:#0c1525;--border:rgba(255,255,255,0.055);--border2:rgba(255,255,255,0.1);--cyan:#00d4ff;--cyan2:#0099cc;--rose:#ff4d6d;--rose-g:rgba(255,77,109,0.1);--lime:#a3e635;--lime-g:rgba(163,230,53,0.11);--t1:#deeaf2;--t2:#6e90a8;--t3:#2e4d62;--t4:#1b3044;--r:14px;--r2:9px;}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Outfit',sans-serif;font-weight:300;background:var(--c0);color:var(--t1);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
canvas#bg{position:fixed;inset:0;z-index:0;pointer-events:none;opacity:.5}
.rw{position:relative;z-index:1;width:100%;max-width:500px}
.rc{background:var(--c1);border:1px solid var(--border2);border-radius:var(--r);padding:36px 32px;animation:riseUp .5s ease both}
@keyframes riseUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
.logo{display:flex;align-items:center;gap:11px;margin-bottom:24px;justify-content:center}
.logo-icon{width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,var(--cyan),var(--cyan2));display:flex;align-items:center;justify-content:center;font-size:20px;box-shadow:0 0 22px rgba(0,212,255,.4)}
.logo-text{font-size:22px;font-weight:200;letter-spacing:.03em;background:linear-gradient(135deg,#c8dfe8 30%,var(--cyan));-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.logo-text b{font-weight:500}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-group{margin-bottom:16px}
.form-group label{display:block;font-size:10px;font-weight:500;letter-spacing:.12em;text-transform:uppercase;color:var(--t3);margin-bottom:6px}
.form-control{width:100%;padding:11px 14px;border-radius:var(--r2);border:1px solid var(--border2);background:var(--c2);color:var(--t1);font-family:'Outfit',sans-serif;font-size:13px;outline:none;transition:all .2s}
.form-control:focus{border-color:var(--cyan);box-shadow:0 0 0 3px rgba(0,212,255,.1)}
.btn-r{width:100%;padding:12px;border:none;border-radius:var(--r2);background:linear-gradient(135deg,var(--cyan),var(--cyan2));color:#000;font-size:14px;font-weight:500;cursor:pointer;transition:all .2s;box-shadow:0 0 16px rgba(0,212,255,.28);margin-top:10px}
.btn-r:hover{transform:translateY(-2px)}
.err{background:var(--rose-g);color:var(--rose);border:1px solid rgba(255,77,109,.18);padding:10px 14px;border-radius:var(--r2);font-size:12px;margin-bottom:16px;text-align:center}
.suc{background:var(--lime-g);color:var(--lime);border:1px solid rgba(163,230,53,.2);padding:10px 14px;border-radius:var(--r2);font-size:12px;margin-bottom:16px;text-align:center}
.foot{text-align:center;margin-top:20px;font-size:11px;color:var(--t4)}
.foot a{color:var(--cyan);text-decoration:none}
</style>
</head>
<body>
<canvas id="bg"></canvas>
<div class="rw">
  <div class="rc">
    <div class="logo">
      <div class="logo-icon">⚡</div>
      <span class="logo-text"><b>Event</b>Core</span>
    </div>
    <p style="text-align:center;font-size:14px;color:var(--t2);margin-bottom:24px;">Crear nueva cuenta administrativa</p>
    @if($errors->any())
      <div class="err">{{ $errors->first() }}</div>
    @endif
    @if(session('success'))
      <div class="suc">{{ session('success') }}</div>
    @endif
    <form method="POST" action="/register">
      @csrf
      <div class="form-row">
        <div class="form-group">
          <label>Nombres</label>
          <input type="text" name="nombres" class="form-control" value="{{ old('nombres') }}" placeholder="Ej. Juan" required>
        </div>
        <div class="form-group">
          <label>Apellidos</label>
          <input type="text" name="apellidos" class="form-control" value="{{ old('apellidos') }}" placeholder="Ej. Pérez" required>
        </div>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="juan@email.com" required>
      </div>
      <div class="form-group">
        <label>Contraseña</label>
        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
      </div>
      <div class="form-group">
        <label>Rol solicitado</label>
        <select name="id_rol" class="form-control" required>
          <option value="">Selecciona un rol...</option>
          @foreach($roles as $rol)
            <option value="{{ $rol->id_rol }}" {{ old('id_rol') == $rol->id_rol ? 'selected' : '' }}>
              {{ $rol->nombre_rol }}
            </option>
          @endforeach
        </select>
      </div>
      <button type="submit" class="btn-r">Completar Registro</button>
    </form>
    <div class="foot">¿Ya tienes cuenta? <a href="/login">Inicia sesión</a></div>
  </div>
</div>
<script>
const cnv=document.getElementById('bg'),c=cnv.getContext('2d');let W,H,pts=[];
const resize=()=>{W=cnv.width=window.innerWidth;H=cnv.height=window.innerHeight};resize();window.addEventListener('resize',resize);
class Pt{constructor(){this.init()}init(){this.x=Math.random()*W;this.y=Math.random()*H;this.vx=(Math.random()-.5)*.22;this.vy=(Math.random()-.5)*.22;this.r=Math.random()*1.1+.3;this.a=Math.random()*.35+.08;this.col=Math.random()>.5?'0,212,255':'139,92,246';this.life=0;this.max=Math.random()*350+180}tick(){this.x+=this.vx;this.y+=this.vy;this.life++;if(this.life>this.max||this.x<0||this.x>W||this.y<0||this.y>H)this.init()}draw(){c.beginPath();c.arc(this.x,this.y,this.r,0,Math.PI*2);c.fillStyle=`rgba(${this.col},${this.a})`;c.fill()}}
for(let i=0;i<50;i++)pts.push(new Pt());
(function frame(){c.clearRect(0,0,W,H);pts.forEach(p=>{p.tick();p.draw()});requestAnimationFrame(frame);})();
</script>
</body>
</html>
