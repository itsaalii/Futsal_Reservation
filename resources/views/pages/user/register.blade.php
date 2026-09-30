@extends('layouts.app')

@section('title', 'Register | Jaya Futsal')
@section('body-class', 'auth-page auth-register')

@push('styles')
    <style>
        body.auth-register {
            background-image: linear-gradient(rgba(15, 32, 39, 0.8), rgba(32, 58, 67, 0.8), rgba(44, 83, 100, 0.8)), url('https://images.unsplash.com/photo-1526232761682-d26e03ac148e?q=80&w=1929&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .auth-form-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            border-top: 6px solid #fd7e14;
            position: relative;
            overflow: hidden;
        }

        .auth-form-card::after {
            content: "";
            position: absolute;
            top: -40px;
            right: -40px;
            width: 180px;
            height: 180px;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="%23fd7e14" opacity="0.08"><path d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256 256-114.6 256-256S397.4 0 256 0zM256 464c-114.7 0-208-93.3-208-208S141.3 48 256 48s208 93.3 208 208-93.3 208-208 208z"/><path d="M256 128c-70.6 0-128 57.4-128 128s57.4 128 128 128 128-57.4 128-128-57.4-128-128-128zm0 208c-44.1 0-80-35.9-80-80s35.9-80 80-80 80 35.9 80 80-35.9 80-80 80z"/></svg>');
            background-repeat: no-repeat;
            z-index: 0;
            pointer-events: none;
        }

        .auth-form-card>* {
            position: relative;
            z-index: 1;
        }

        .auth-title {
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: 1.5px;
            font-size: 3rem;
            color: #1a1a1a;
        }

        .section-kicker {
            font-weight: 800;
            color: #fd7e14;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-accent {
            background-color: #fd7e14;
            color: white;
            font-weight: 700;
            border: none;
            padding: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-accent:hover {
            background-color: #e86e04;
            color: white;
        }

        .auth-ornament {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            font-weight: bold;
            color: #6c757d;
        }

        .auth-ornament .ball {
            width: 12px;
            height: 12px;
            background-color: #fd7e14;
            border-radius: 50%;
            display: inline-block;
        }

        .auth-ornament .lines {
            flex-grow: 1;
            height: 2px;
            background: dashed 2px #dee2e6;
        }

        .back-link {
            color: #6c757d;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .back-link:hover {
            color: #fd7e14;
        }
    </style>
@endpush

@section('content')

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-10">
                <div class="panel-box auth-form-card">
                    <p class="section-kicker mb-2">Jaya Futsal Arena</p>

                    <h1 class="auth-title mb-2">Register Tim</h1>

                    <div class="auth-ornament">
                        <span class="ball"></span>
                        <span class="lines"></span>
                        <span>Formasi Tim Baru</span>
                    </div>

                    <p class="text-dark-70 mb-4">
                        Buat akun dulu, lalu lanjut proses reservasi lapangan.
                    </p>

                    <form class="row g-3" method="POST" action="{{ route('proses-register') }}">
                        @csrf
                        <div class="col-12">
                            <label class="form-label fw-bold" for="registerName">Nama </label>
                            <input class="form-control form-control-lg" id="nama" name="nama" type="text"
                                placeholder="Contoh: Mr. X" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="registerPhone">No. HP</label>
                            <input class="form-control form-control-lg" id="registerPhone" name="no_hp" type="text"
                                placeholder="08xxxxxxxxxx" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="registerEmail">Email</label>
                            <input class="form-control form-control-lg" id="registerEmail" name="email" type="email"
                                placeholder="tim@email.com" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="registerPassword">Password</label>
                            <div class="input-group">
                                <input class="form-control form-control-lg border-end-0" name="password" id="registerPassword"
                                    type="password" placeholder="Minimal 8 karakter" />
                                <span class="input-group-text bg-white" style="cursor: pointer;" onclick="togglePassword('registerPassword', 'toggleIcon-registerPassword')">
                                    <i class="bi bi-eye-slash text-secondary" id="toggleIcon-registerPassword"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="registerConfirm">Konfirmasi Password</label>
                            <div class="input-group">
                                <input class="form-control form-control-lg border-end-0" name="password_confirmation" id="registerConfirm"
                                    type="password" placeholder="Ulangi password" />
                                <span class="input-group-text bg-white" style="cursor: pointer;" onclick="togglePassword('registerConfirm', 'toggleIcon-registerConfirm')">
                                    <i class="bi bi-eye-slash text-secondary" id="toggleIcon-registerConfirm"></i>
                                </span>
                            </div>
                        </div>

                        @push('scripts')
                        <script>
                            function togglePassword(inputId, iconId) {
                                const input = document.getElementById(inputId);
                                const icon = document.getElementById(iconId);
                                if (input.type === 'password') {
                                    input.type = 'text';
                                    icon.classList.remove('bi-eye-slash');
                                    icon.classList.add('bi-eye');
                                    icon.classList.remove('text-secondary');
                                    icon.classList.add('text-primary');
                                } else {
                                    input.type = 'password';
                                    icon.classList.remove('bi-eye');
                                    icon.classList.add('bi-eye-slash');
                                    icon.classList.remove('text-primary');
                                    icon.classList.add('text-secondary');
                                }
                            }
                        </script>
                        @endpush
                        <div class="col-md-12">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div class="col-12 mt-4">
                            <button class="btn btn-accent w-100" type="submit">Buat Akun Baru</button>
                        </div>
                    </form>
                    <div class="col-12 text-center small mt-3">
                        Sudah punya akun?
                        <a href="{{ url('/login') }}" class="fw-bold link-dark text-decoration-underline">
                            Login sekarang
                        </a>
                    </div>
                    <div class="text-center">
                        <a href="{{ url('/') }}" class="back-link d-inline-block mt-4">
                            &larr; Kembali ke Landing Page
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
