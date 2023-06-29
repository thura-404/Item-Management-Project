@extends('layouts.form')

@section('form-nav-title')
Item Register
@endsection


@section('form-nav-item-link')
{{ route('items.excel-form') }}
@endsection


@section('form-nav-item-text')
Add Excel File
@endsection


@section('form-scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {

            var reader = new FileReader();

            reader.onload = function(e) {
                $('.image-upload-wrap').hide();

                $('.file-upload-image').attr('src', e.target.result);
                $('.file-upload-content').show();

                $('.image-title').html(input.files[0].name);
            };

            reader.readAsDataURL(input.files[0]);

        } else {
            removeUpload();
        }
    }

    function removeUpload() {
        $('.file-upload-input').replaceWith($('.file-upload-input').clone());
        $('.file-upload-input').val('');
        $('.file-upload-content').hide();
        $('.image-upload-wrap').show();
    }
    $('.image-upload-wrap').bind('dragover', function() {
        $('.image-upload-wrap').addClass('image-dropping');
    });
    $('.image-upload-wrap').bind('dragleave', function() {
        $('.image-upload-wrap').removeClass('image-dropping');
    });

    // Add Category (* On click save button)
    $('#saveCategoryButton').click(function() {
        var categoryName = $('#CategoryName').val();

        // Check if category name is empty
        if (categoryName == '') {
            alert('Category name cannot be empty');
            return;
        }

        // Check if category already exists
        var exists = false;
        $('#cbocategories option').each(function() {
            if ($(this).text() == categoryName) {
                exists = true;
                alert('Category already exists!');
                return;
            }
        });

        // Perform AJAX request to save the category
        $.ajax({
            url: "{{ route('categories.register') }}",
            method: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "name": categoryName
            },
            success: function(response) {

                // Clear the select box options
                $('#cbocategories').empty();

                // Add the new category options to the select box
                $.each(response, function(index, category) {
                    var option = $('<option>').text(category.name).val(category.id);
                    $('#cbocategories').append(option);
                });

                // Hide the dialog box and clear the input
                $('#saveCategory').hide();
                $('#CategoryName').val('');

                //Remove background backdrop
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();


            },
            error: function(xhr, status, error) {

                // Hide the dialog box and clear the input
                $('#saveCategory').hide();
                $('#CategoryName').val('');

                //Remove background backdrop
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();

                // Show error message
                if (xhr.status == 422) {
                    var errors = xhr.responseJSON.errors;
                    var errorHtml = '<ul>';
                    $.each(errors, function(key, value) {
                        errorHtml += '<li>' + value[0] + '</li>';
                    });
                    errorHtml += '</ul>';

                    $('#error-message').show();
                    $('#error-message .card-body').html(errorHtml);
                    setTimeout(function() {
                        $('#error-message').hide();
                    }, 3000);
                }
            }
        });
    });


    // show deletable categories (On click minus button)
    $('#btnMinus').click(function() {

        $.ajax({
            url: "{{ route('categories.deletable') }}",
            method: "GET",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            success: function(response) {
                //Update the select box with the updated category options
                $('#cboDelCategories').html(response.html);

            },
            error: function(xhr, status, error) {
                // Show error message
                alert('Error', 'Failed to fetch categories');
            }
        });

    });

    // delete category (* On click delete button)
    $('#deleteCategoryButton').click(function() {
        var categoryId = $('#cboDelCategories').val();
        console.log(categoryId);

        // Perform AJAX request to delete the category
        $.ajax({
            url: "{{ route('categories.delete') }}",
            method: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "id": categoryId
            },
            success: function(response) {

                // Clear the select box options
                $('#cbocategories').empty();

                // Add the new category options to the select box
                $.each(response, function(index, category) {
                    var option = $('<option>').text(category.name).val(category.id);
                    $('#cbocategories').append(option);
                });

                // Hide the dialog box and clear the input
                $('#deleteCategory').hide();

                //Remove background backdrop
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            },
            error: function(xhr, status, error) {
                console.log('error');
                console.log(error);
            }
        });
    });
</script>
@endsection