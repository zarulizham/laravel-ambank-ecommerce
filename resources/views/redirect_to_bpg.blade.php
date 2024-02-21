<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js" defer></script>
    <style>
        label {
            font-weight: 200;
            color: grey
        }
    </style>
</head>

<body>
    <div class="d-flex justify-content-center mt-5 d-none">
        <div class="col-md-5">
            <div class="text-center">
                <h5>eCommerce</h5>
            </div>
            <div class="card">
                <div class="card-body">
                    <form x-data x-init="$refs.submit.click()" action="{{ config('ecommerce.payment_window_url') }}" method="post">
                        @foreach ($request->list() as $index => $data)
                            <div class="form-group mb-3">
                                <label for="">{{ $index }}</label>
                                <input class="form-control" type="hidden" name="{{ $index }}" value="{{ $data }}">
                            </div>
                        @endforeach
                        <button x-ref="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
