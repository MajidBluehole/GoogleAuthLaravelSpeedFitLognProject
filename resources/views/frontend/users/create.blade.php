<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords"
        content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="{{url('frontendimg/icons/icon-48x48.png')}}" />
    <link rel="canonical" href="https://demo-basic.adminkit.io/pages-sign-up.html" />
    <title>Sign Up | AdminKit Demo</title>
    <link href="{{url('frontend/css/app.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <main class="d-flex w-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">
                        <div class="text-center mt-4">
                            <h1 class="h2">Get started</h1>
                            <p class="lead">
                                Start creating the best possible user experience for your customers.
                            </p>
                        </div>

                        <div class="card">
    <div class="card-body">
        <div class="m-sm-4">
            <form action="{{ route('users.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="name">Name</label>
                    <input class="form-control form-control-lg" type="text" id="name" name="name" placeholder="Enter your name" required />
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control form-control-lg" type="email" id="email" name="email" placeholder="Enter your email" required />
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="mobile">Mobile</label>
                    <input class="form-control form-control-lg" type="tel" id="mobile" name="mobile" placeholder="Enter your mobile" required />
                    @error('mobile')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Password</label>
                    <input class="form-control form-control-lg" type="password" id="password" name="password" placeholder="Enter password" required />
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="file">Upload File</label>
                    <input class="form-control form-control-lg" type="file" id="file" name="file" />
                    @error('file')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="country">Country</label>
                    <select class="form-control form-control-lg" id="country" name="country_id" required>
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="state">State</label>
                    <select class="form-control form-control-lg" id="state" name="state_id" required>
                        <option value="">Select State</option>
                        <!-- State options will be populated dynamically based on selected country -->
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="city">City</label>
                    <select class="form-control form-control-lg" id="city" name="city_id" required>
                        <option value="">Select City</option>
                        <!-- City options will be populated dynamically based on selected state -->
                    </select>
                </div>
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-lg btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>


                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="js/app.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.querySelector('form');
            form.addEventListener('submit', function (event) {
                let isValid = true;

                // Add client-side validation logic here
                const name = document.querySelector('input[name="name"]').value;
                const email = document.querySelector('input[name="email"]').value;
                const mobile = document.querySelector('input[name="mobile"]').value;
                const country = document.querySelector('select[name="country_id"]').value;
                const state = document.querySelector('select[name="state_id"]').value;
                const city = document.querySelector('select[name="city_id"]').value;

                if (!name || !email || !mobile || !country || !state || !city) {
                    isValid = false;
                    alert('Please fill out all required fields.');
                }

                if (!isValid) {
                    event.preventDefault();
                }
            });

            // Add dependent dropdown logic here
            document.querySelector('select[name="country_id"]').addEventListener('change', function () {
                const countryId = this.value;
                fetch(`/states/${countryId}`)
                    .then(response => response.json())
                    .then(states => {
                        const stateSelect = document.querySelector('select[name="state_id"]');
                        stateSelect.innerHTML = '';
                        states.forEach(state => {
                            stateSelect.innerHTML += `<option value="${state.id}">${state.name}</option>`;
                        });
                        stateSelect.dispatchEvent(new Event('change'));
                    });
            });

            document.querySelector('select[name="state_id"]').addEventListener('change', function () {
                const stateId = this.value;
                fetch(`/cities/${stateId}`)
                    .then(response => response.json())
                    .then(cities => {
                        const citySelect = document.querySelector('select[name="city_id"]');
                        citySelect.innerHTML = '';
                        cities.forEach(city => {
                            citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
                        });
                    });
            });
        });
    </script>

</body>

</html>