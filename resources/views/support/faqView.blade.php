@extends('layouts.mainPage')

@section('title')
    FAQ
@endsection

@section('css')
<style>
    .container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
</style>
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>FAQ</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('faq.seeall') }}">Bantuan</a></li>
                <li class="breadcrumb-item active">FAQ</li>
            </ol>
        </nav>
    </div>
@endsection

@section('sectionPage')
    @if(session('successMessage'))
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            {{ session('successMessage') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

            <div class="card">
                <div class="card-body" style="margin-top: 24px">
                    @if (Auth::user()->role != 'student')
                        <a href="{{ route('faq.createForm') }}">
                            <button type="button" class="btn btn-primary"><i class="fa-solid fa-plus" style="margin-right: 8px;"></i>Tambah FAQ Baru</button>
                        </a>
                        <br>
                        <br>
                    @endif

                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        @if ($faqs->count() == 0)
                        <div class="container">
                            <span style="color: dimgray">Belum ada FAQ</span>
                        </div>
                        @endif
                        @foreach ($faqs as $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="{{ "flush-heading-" . $faq->id }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="{{ "#flush-collapse-" . $faq->id }}" aria-expanded="false" aria-controls="{{ "flush-collapse-" . $faq->id }}">
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="{{ "flush-collapse-" . $faq->id }}" class="accordion-collapse collapse" aria-labelledby="{{ "flush-heading-" . $faq->id }}" data-bs-parent="#accordionFlush">
                                    <div class="accordion-body">{!! nl2br(e($faq->answer)) !!}</div>
                                    @if (Auth::user()->role != 'student')
                                        <div class="action">
                                            <a href="{{ route('faq.updateForm', $faq->id) }}">
                                                <button type="button" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></button>
                                            </a>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="{{"#deleteFaqModal_" . $faq->id}}">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>

                                            {{-- Modal --}}
                                            <div class="modal fade" id="{{"deleteFaqModal_" . $faq->id}}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body" style="text-align: center;">
                                                    <h5 class="modal-title" style="font-weight: 700">Yakin mau hapus FAQ ini?</h5>
                                                    Data yang udah terhapus akan sulit untuk dikembalikan seperti semula
                                                    </div>
                                                    <div class="modal-footer border-0" style="flex-wrap: nowrap;">
                                                    <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Tidak</button>
                                                    <form class="w-100" action="{{ route('faq.delete', ['id' => $faq->id]) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn btn-secondary w-100">Ya, hapus</button>
                                                    </form>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            </div>

            
@endsection

@section('script')
<script>
    
</script>
@endsection