@extends('layouts.mainpage')

@section('title')
    Semua Pengguna
@endsection

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Urus Pengguna</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('manage.users.seeall') }}">Pengguna</a></li>
            <li class="breadcrumb-item active">Urus Pengguna</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
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
                        <div id="actions-user" style="display: flex; justify-content: space-between;">
                            <div id="search-user" class="search-bar">
                                <form class="input-group" action="{{ route('manage.users.search') }}">
                                    <input type="text" name="userName" class="form-control" placeholder="Cari pengguna" title="Enter search keyword" value="{{ isset($searchNameParam) ? $searchNameParam : '' }}">
                                    <button type="submit" class="btn btn-primary" title="Search"><i class="bi bi-search"></i></button>
                                </form>
                            </div>
                            <div id="action-button-user">
                                <a href="{{ route('manage.users.register') }}">
                                    <button type="button" class="btn btn-primary">Tambah Pengguna Baru</button>
                                </a>

                                <a href="{{ route('manage.users.importstudents') }}">
                                    <button type="button" class="btn btn-primary">Import Murid</button>
                                </a>

                                <button type="button" id="delete-user-button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUsersModal" disabled>Hapus</button>
                                {{-- Modal --}}
                                <div class="modal fade" id="deleteUsersModal" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" style="text-align: center;">
                                            <h5 class="modal-title" style="font-weight: 700">Yakin mau hapus pengguna berikut?</h5>
                                            <div id="checked-count-info"><strong>0</strong> pengguna yang sudah terhapus akan sulit untuk dikembalikan seperti semula</div>
                                        </div>
                                        <div class="modal-footer border-0" style="flex-wrap: nowrap;">
                                        <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Tidak</button>
                                        <button type="submit" id="deleteSelectedUsersData" class="btn btn-secondary w-100">Ya, hapus</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>
                        <br>
                        <!-- Default Table -->
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">
                                    <input class="form-check-input" id="select-all-checkboxes" type="checkbox">
                                </th>
                                <th scope="col">Nama</th>
                                <th scope="col">Status</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>
                                    <input class="form-check-input" type="checkbox" name="user_id[]" value="{{ $user->id }}">
                                </td>
                                <td>{{ $user->name }}</td>
                                <td>
                                    @if ($user->isSuspended == true)
                                        Ter-suspend
                                    @else
                                        Aktif
                                    @endif
                                </td>
                                <td style="display: flex; justify-content: end;">
                                    <a href="{{ route('manage.users.detail', $user->id) }}">
                                        <button type="button" class="btn btn-primary">Detail</button>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="row mt-5">
                            <div class="d-flex justify-content-end">
                                {{ $users->appends(['checked_users_session' => session('checked_users_session')])->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>  
@endsection

@section('css')
    <style>
        @media screen and (max-width: 600px) {
            #actions-user {
                display: block !important;
            }

            #action-button-user {
                margin-top: 12px;
            }
        }
    </style>
@endsection

@section('script')
    <script>
        function setSessionValue(key, value) {
            try {
                sessionStorage.setItem(key, value);
            } catch (error) {
                console.error('Session storage error:', error);
            }
        }

        function getSessionValue(key) {
            return sessionStorage.getItem(key);
        }


        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="user_id[]"]');
            const deleteButton = document.getElementById('delete-user-button');
            const selectAllCheckbox = document.getElementById('select-all-checkboxes');
            const checkedCountInfo = document.getElementById('checked-count-info').querySelector('strong');

            function setCheckboxesState(checked) {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = checked;
                });
            }

            function toggleCheckboxes() {
                const checked = selectAllCheckbox.checked;
                setCheckboxesState(checked);
                const userIds = checked ? Array.from(checkboxes).map(checkbox => checkbox.value) : [];
                setSessionValue('checked_users_session', userIds.join(','));

                updateCheckedCount();
                deleteButton.disabled = !checked;
            }

            function addCheckboxValuesToSession() {
                const checkedUserIds = Array.from(checkboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);
                const sessionIds = getSessionValue('checked_users_session') ? getSessionValue('checked_users_session').split(',') : [];
                const updatedIds = [...new Set([...sessionIds, ...checkedUserIds])];
                setSessionValue('checked_users_session', updatedIds.join(','));

                updateCheckedCount();
            }
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const userId = this.value;
                    if (this.checked) {
                        addCheckboxValuesToSession();
                    } else {
                        let sessionIds = getSessionValue('checked_users_session') ? getSessionValue('checked_users_session').split(',') : [];
                        sessionIds = sessionIds.filter(id => id !== userId);
                        setSessionValue('checked_users_session', sessionIds.join(','));
                    }
                    
                    selectAllCheckbox.checked = Array.from(checkboxes).every(checkbox => checkbox.checked);
                    deleteButton.disabled = document.querySelectorAll('input[name="user_id[]"]:checked').length === 0;
                });
            });

            selectAllCheckbox.addEventListener('change', function() {
                toggleCheckboxes();
            });

            function updateCheckedCount() {
                const checkedCount = document.querySelectorAll('input[name="user_id[]"]:checked').length;
                checkedCountInfo.textContent = checkedCount;
            }


            const checkedUsersArray = getSessionValue('checked_users_session') ? getSessionValue('checked_users_session').split(',') : [];
            checkboxes.forEach(checkbox => {
                const userId = checkbox.value;
                checkbox.checked = checkedUsersArray.includes(userId);
            });
            selectAllCheckbox.checked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            updateCheckedCount();
            
            deleteButton.disabled = checkedUsersArray.length === 0;
        });

        function sendSessionDataToController() {
            const checkedUsers = getSessionValue('checked_users_session') || '';
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                type: 'POST',
                url: '/manage/users/delete/selected',  
                data: {
                    checkedUsers: checkedUsers,
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken 
                },
                success: function(response) {
                    console.log('Response received:', response);
                    if (response && response.redirectUrl) {
                        sessionStorage.clear();
                        window.location.href = response.redirectUrl;
                    } else {
                        console.error('Invalid response:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error sending data:', error);
                }
            });
        }

        document.getElementById('deleteSelectedUsersData').addEventListener('click', function() {
            sendSessionDataToController();
        });
    </script>
@endsection