@extends('layouts.master-blank')

@section('content')
    <style>
        #pdf-container {
            /* border: 1px solid black;
                            width: 80% */
        }
        #pdf-canvas {
        border: 1px solid black;
        }

        .wrapper-page {
            max-width: 612px !important;
        }

        body {
            background: #2a3142;
        }

        .page-count {
            /* background-color: #ffffff;
            padding: 2px 5px;
            color: #626ed4; */
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@2.7.570/build/pdf.min.js"></script>
    @php
        $slug = get_user_role(auth()->user()->id);
    @endphp
    <div class="wrapper-page">
        <div class="row">
            <div class="col-12">
                <div class="bg-primary p-4 text-white text-center position-relativey">
                    <h1>{{$page_title}}</h1>
                    {{-- <p>Before using our service, you must agree to our policies:</p> --}}
                </div>
                <button id="prev-page" class="btn btn-info my-2 mx-2">Previous Page</button>
                <button id="next-page" class="btn btn-primary my-2">Next Page</button>
                <span class="page-count btn btn-secondary my-2 mx-2">Page 
                    <span id="page-num"></span> of <span id="page-count"></span></span>
                <br>

                <div id="pdf-container"></div>
                <div class="bg-light p-4">
                    <form action="{{route('accept.policies')}}" method="post">
                        @csrf
                        @if ($slug == 'employee')
                            <input type="checkbox" name="accept" id="accept" required>
                            <label for="accept">I have read and agree to the policies.</label>
                            <br>
                        @endif
                        <div class="text-right">
                            <a class="btn btn-info btn-rounded dropdown-toggle" href="/download-pdf/{{ $policy_file_name }}"
                                type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"
                                download>
                                <i class="mdi mdi-cloud-download-outline"></i> Download policies
                            </a>
                            @if ($slug == 'employee')
                                <button class="btn btn-primary" type="submit">Accept & continue</button>
                            @endif
                        </div>
                    </form>
                </div>

            </div> <!-- end col -->
        </div>
    </div>
    <script>
        // Load PDF document
        let pdfDoc = null;
        let pageNum = 1;
        const prevButton = document.getElementById('prev-page');
        const nextButton = document.getElementById('next-page');
        const pageNumSpan = document.getElementById('page-num');
        const pageCountSpan = document.getElementById('page-count');
        var canvas = document.createElement('canvas');
        var context = canvas.getContext('2d');
        pdfjsLib.getDocument("{{ asset('storage/assets/documents/' . $policy_file_name) }}").promise.then(function(pdf) {
            // Get the first page of the PDF document
            console.log(pdf)
            pdfDoc = pdf;
            pageCountSpan.textContent = pdfDoc.numPages;
            prevButton.addEventListener('click', () => {
                if (pageNum <= 1) {
                    return;
                }
                pageNum--;
                renderPage(pageNum);
            });
            nextButton.addEventListener('click', () => {
                if (pageNum >= pdfDoc.numPages) {
                    return;
                }
                pageNum++;
                renderPage(pageNum);
            });
            // Render first page
            renderPage(pageNum);

            pdf.getPage(1).then(function(page) {
                // Set the scale of the page
                var scale = 1;
                // Get the viewport of the page at the specified scale
                var viewport = page.getViewport({
                    scale: scale
                });
                // Create a canvas element to render the page
                // var canvas = document.createElement('canvas');
                canvas.width = viewport.width;
                canvas.height = viewport.height;
                // Append the canvas element to the container
                document.getElementById('pdf-container').appendChild(canvas);
                // Get the context of the canvas element
                // Render the page to the canvas element
                page.render({
                    canvasContext: context,
                    viewport: viewport
                });
            });


            function renderPage(num) {
                pdfDoc.getPage(num).then((page) => {
                    // Set canvas dimensions to match PDF page dimensions
                    const viewport = page.getViewport({
                        scale: 1
                    });
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page on canvas
                    const renderContext = {
                        canvasContext: context,
                        viewport: viewport,
                    };
                    page.render(renderContext);

                    // Update page number display
                    pageNumSpan.textContent = num;
                });
            }
        });
    </script>
    <!-- end wrapper-page -->
    {{-- @include('includes.add_review') --}}
@endsection
@section('script')
@endsection
