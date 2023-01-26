<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>afiliDrop</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <!-- Favicon -->
    <link href="{{ asset('/img/favicon.ico"') }} rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Roboto:wght@700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/flaticon/font/flaticon.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href=" {{ asset('/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous" />

    <!-- Template Stylesheet -->
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Hero Start -->
    <div class="container-fluid bg-primary py-5 mb-5 hero-header">
        <div class="container py-5">
            <div class="row justify-content-start">
                <div class="col-lg-8 text-center text-lg-start">

                    <p class="fs-2 text-white mb-lg-2"></p>
                    <div class="d-flex align-items-center justify-content-center justify-content-lg-start pt-5">
                        <a href="" class="btn btn-outline-light border-2 py-md-3 px-md-5 me-5">Voltar Site</a>
                        <button type="button" class="btn-play" data-bs-toggle="modal"
                            data-src="https://www.youtube.com/embed/DWRcNpR6Kdc" data-bs-target="#videoModal">
                            <span></span>
                        </button>
                        <h5 class="font-weight-normal text-white m-0 ms-4 d-none d-sm-block">Play Video</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Video Modal Start -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Youtube Video</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- 16:9 aspect ratio -->
                    <div class="ratio ratio-16x9">
                        <iframe class="embed-responsive-item" src="" id="video" allowfullscreen
                            allowscriptaccess="always" allow="autoplay"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Video Modal End -->

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
