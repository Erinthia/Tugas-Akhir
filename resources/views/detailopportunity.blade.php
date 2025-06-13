<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>SchoolTech Indonesia - Career</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Saira:wght@500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body>

    <!-- Navbar Start -->
    <div class="container-fluid bg-primary">
        <div class="container">
            <nav class="navbar navbar-dark navbar-expand-lg py-0">
                <a href="index.html" class="navbar-brand">
                    <img src="{{ asset('assets/img/white-color-horizontal.png') }}" alt="logo" height="55px"
                        width="193px">
                </a>
                <button type="button" class="navbar-toggler me-0" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse bg-transparent" id="navbarCollapse">

                </div>
                <div class="d-none d-xl-flex flex-shirink-0">
                    <div id="phone-tada" class="d-flex align-items-center justify-content-center me-4">
                        <a href="" class="position-relative animated tada infinite">
                            <i class="fa fa-phone-alt text-white fa-2x"></i>
                            <div class="position-absolute" style="top: -7px; left: 20px;">
                                <span><i class="fa fa-comment-dots text-secondary"></i></span>
                            </div>
                        </a>
                    </div>
                    <div class="d-flex flex-column pe-4 border-end">
                        <span class="text-white-50">Have any questions?</span>
                        <span class="text-secondary">Call: +62 881 0828 61608</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-center ms-4 ">
                        <a href="#"><i class="bi bi-search text-white fa-2x"></i> </a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->

    <div class="container-fluid services py-5 mb-5">
        <div class="container">
            <div class="mx-auto pb-5 wow fadeIn" data-wow-delay=".3s">
                <a href="{{ route('welcome') }}">
                    <button class="btn-primary">Back</button>
                </a>
            </div>
            <div class="text-center mx-auto pb-5 wow fadeIn" data-wow-delay=".3s" style="max-width: 600px;">
                <h5 class="text-primary">Detail Opportunity</h5>
                <h1>{{ $opportunity->name }}</h1>
            </div>
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible show fade">
                    <div class="alert-body">
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            <div class="mx-auto pb-5 wow fadeIn" data-wow-delay=".3s">
                <div class="alert alert-warning" role="alert">
                    Kegiatan ini tidak dipungut biaya apapun! Untuk mendaftar posisi silahkan melengkapi form berikut
                    dengan benar. Informasi terkait posisi yang dibuka dapat anda lihat pada bagian kiri halaman ini.
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <p><strong>Opportunity Description</strong></p>
                        <p>{{ $opportunity->description }}</p>
                        <hr>
                        <p><strong>Opportunity Job Description</strong></p>
                        <p>{{ $opportunity->job_description }}</p>
                        <hr>
                        <p><strong>Opportunity Requirement</strong></p>
                        <p>{{ $opportunity->job_requirements }}</p>
                        <hr>
                        <p><strong>Opportunity Division</strong></p>
                        <p>{{ $opportunity->division->name }}</p>
                        <hr>
                        <p><strong>Opportunity Category</strong></p>
                        <p>{{ $opportunity->category->name }}</p>
                        <hr>
                        <p><strong>Workingspace Information</strong></p>
                        <p>{{ $opportunity->location }} | {{ $opportunity->schema->name }}</p>
                        <hr>
                        <p><strong>Open Apply Period</strong></p>
                        <p>{{ $opportunity->start_date }} until {{ $opportunity->end_date }}</p>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <form action="{{ route('simpanDt', ['id' => $opportunity->id]) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <p><strong>Applicant Form</strong></p>
                            <div class="wow fadeIn" data-wow-delay=".3s">
                                <div class="p-5 rounded contact-form">
                                    <div class="mb-4">
                                        <p class="text-white">Full Name</p>
                                        <input type="text" class="form-control border-0 py-3" name="fullname">
                                        @error('fullname')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-white">Email Address</p>
                                        <input type="email" class="form-control border-0 py-3" name="email">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-white">Phone Number</p>
                                        <input type="text" class="form-control border-0 py-3" name="phone_number">
                                        @error('phone_number')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-white">Portfolio Link</p>
                                        <input type="text" class="form-control border-0 py-3"
                                            name="portfolio_link">
                                        @error('portfolio_link')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-white">Curriculum Vitae</p>
                                        <input type="file" class="form-control border-0 py-3" name="cv_file">
                                        @error('cv_file')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <p class="text-white">*Only PDF files are allowed for CV | max 2048 kb</p>
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-white">Gender</p>
                                        <select class="form-control border-0 py-3" name="gender_id">
                                            <option value="">Select Gender</option>
                                            @foreach ($genders as $gender)
                                                <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('gender_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-white">Birth Date</p>
                                        <input type="date" class="form-control border-0 py-3" name="birth_date">
                                        @error('birth_date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-white">Domicile Address</p>
                                        <input type="text" class="form-control border-0 py-3"
                                            name="domicile_address">
                                        @error('domicile_address')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-white">Religion</p>
                                        <select class="form-control border-0 py-3" name="religion_id">
                                            <option value="">Select Religion</option>
                                            @foreach ($religions as $religion)
                                                <option value="{{ $religion->id }}">{{ $religion->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('religion_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-white">Marital Status</p>
                                        <select class="form-control border-0 py-3" name="marital_id">
                                            <option value="">Select Status</option>
                                            @foreach ($maritalStatuses as $maritalStatus)
                                                <option value="{{ $maritalStatus->id }}">{{ $maritalStatus->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('marital_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-white">Last Education</p>
                                        <select class="form-control border-0 py-3" name="education_id">
                                            <option value="">Select Education</option>
                                            @foreach ($educations as $education)
                                                <option value="{{ $education->id }}">{{ $education->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('education_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-white">Education Institution</p>
                                        <input type="text" class="form-control border-0 py-3"
                                            name="education_institution">
                                        @error('education_institution')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-white">Majority</p>
                                        <input type="text" class="form-control border-0 py-3" name="majority">
                                        @error('majority')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-white">GPA</p>
                                        <input type="number" step="0.01" class="form-control border-0 py-3"
                                            name="gpa">
                                        @error('gpa')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-white">Graduate Status</p>
                                        <select class="form-control border-0 py-3" name="graduate_status">
                                            <option value="">Select Graduate Status</option>
                                            @foreach ($graduate_status as $status)
                                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('graduate_status')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-white">Graduate Year</p>
                                        <input type="text" class="form-control border-0 py-3"
                                            name="graduate_year">
                                        @error('graduate_year')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-white">Information Source</p>
                                        <select class="form-control border-0 py-3" name="information_from">
                                            <option value="">Select Information Source</option>
                                            @foreach ($informationSource as $information)
                                                <option value="{{ $information->id }}">
                                                    {{ $information->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('information_from')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="text-start">
                                        <button class="btn bg-primary text-white py-3 px-5" type="submit">Send
                                            Application</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-5 services-inner">



        <!-- Footer Start -->
        <div class="container-fluid footer bg-dark wow fadeIn" data-wow-delay=".3s">
            <div class="container pt-5 pb-4">
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <a href="index.html">
                            <img src="{{ asset('assets/img/white-color-horizontal.png') }}" alt="logo"
                                height="55px" width="193px">
                        </a>
                        <p class="mt-4 text-light">SchoolTech berkomitmen menjadi solusi IT terpercaya untuk sekolah,
                            mendukung transformasi digital. Dengan layanan unggulan kami, sekolah dapat mempercepat
                            digitalisasi secara efisien dan optimal.</p>
                        <div class="d-flex hightech-link">
                            <a href="" class="btn-light nav-fill btn btn-square rounded-circle me-2"><i
                                    class="fab fa-instagram text-primary"></i></a>
                            <a href="" class="btn-light nav-fill btn btn-square rounded-circle me-0"><i
                                    class="fab fa-linkedin-in text-primary"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">

                    </div>
                    <div class="col-lg-3 col-md-6">

                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="#" class="h3 text-secondary">Contact Us</a>
                        <div class="text-white mt-4 d-flex flex-column contact-link">
                            <a href="#" class="pb-3 text-light border-bottom border-primary"><i
                                    class="fas fa-map-marker-alt text-secondary me-2"></i> Jl. Gunung Jati RT. 022 RW.
                                005
                                Desa Pandan Landung. Kecamatan Wagir. Kabupaten Malang</a>
                            <a href="#" class="py-3 text-light border-bottom border-primary"><i
                                    class="fas fa-phone-alt text-secondary me-2"></i> +62 881 0828 61608</a>
                            <a href="#" class="py-3 text-light border-bottom border-primary"><i
                                    class="fas fa-envelope text-secondary me-2"></i> admin@schooltechindonesia.com </a>
                        </div>
                    </div>
                </div>
                <hr class="text-light mt-5 mb-4">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start">
                        <span class="text-light"><a href="#" class="text-secondary"><i
                                    class="fas fa-copyright text-secondary me-2"></i>SchoolTech Indonesia</a>, All
                            right
                            reserved.</span>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <!--/* This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. */-->
                        <span class="text-light">Designed By<a href="https://htmlcodex.com"
                                class="text-secondary">HTML
                                Codex</a></span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-secondary btn-square rounded-circle back-to-top"><i
                class="fa fa-arrow-up text-white"></i></a>


        <!-- JavaScript Libraries -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../lib/wow/wow.min.js"></script>
        <script src="../lib/easing/easing.min.js"></script>
        <script src="../lib/waypoints/waypoints.min.js"></script>
        <script src="../lib/owlcarousel/owl.carousel.min.js"></script>

        <!-- Template Javascript -->
        <script src="../js/main.js"></script>
</body>

</html>
