$(document).ready(function () {
    fetchImages();

    // Open/Close Modal
    $('#openModal').click(() => $('#uploadModal').css('display', 'flex'));
    $('#closeModal').click(() => $('#uploadModal').hide());

    // Close Edit Modal
    $('#closeEditModal').click(() => $('#editModal').hide());

    // Fetch Images
    function fetchImages() {
        $.get({
            url: 'actions.php?action=fetch',
            dataType: 'json'
        })
            .done(function (data) {
                try {
                    const images = data;
                    let $tbody = $('#imageTable tbody');
                    $tbody.empty();

                    images.forEach(img => {
                        let $tr = $('<tr>');

                        // Thumbnail
                        let $img = $('<img>').attr('src', `uploads/${img.filename}`).attr('width', 50).addClass('thumb-preview');
                        $tr.append($('<td>').append($img));

                        // Title (XSS protection via .text())
                        $tr.append($('<td>').text(img.title));

                        // Filename
                        $tr.append($('<td>').text(img.filename));

                        // Date
                        $tr.append($('<td>').text(img.created_at));

                        // Actions
                        let $actionsTd = $('<td>');

                        // Edit Button
                        let $editBtn = $('<button>')
                            .addClass('btn-primary') // Using btn-primary for edit
                            .css({ 'padding': '0.4rem 0.8rem', 'font-size': '0.875rem', 'margin-right': '5px' })
                            .attr('data-id', img.id)
                            .attr('data-title', img.title)
                            .addClass('edit-btn')
                            .text('Edit');

                        // Delete Button
                        let $deleteBtn = $('<button>')
                            .addClass('delete-btn')
                            .attr('data-id', img.id)
                            .text('Delete');

                        $actionsTd.append($editBtn).append($deleteBtn);
                        $tr.append($actionsTd);

                        $tbody.append($tr);
                    });
                } catch (e) {
                    console.error("Failed to process images:", e);
                    alert("Error loading images. Please check the console.");
                }
            })
            .fail(function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
                alert("Failed to fetch images.");
            });
    }

    // AJAX Upload
    $('#uploadForm').submit(function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: 'actions.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    $('#uploadModal').hide();
                    $('#uploadForm')[0].reset();
                    fetchImages(); // Refresh table instantly
                } else {
                    alert('Upload failed: ' + (response.message || 'Unknown error'));
                }
            },
            error: function () {
                alert('An error occurred during upload.');
            }
        });
    });

    // Handle Edit Button Click
    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        const title = $(this).data('title');
        $('#editId').val(id);
        $('#editTitle').val(title);
        $('#editModal').css('display', 'flex');
    });

    // AJAX Edit
    $('#editForm').submit(function (e) {
        e.preventDefault();
        let formData = $(this).serialize();
        $.post({
            url: 'actions.php',
            data: formData,
            dataType: 'json'
        })
            .done(function (response) {
                if (response.status === 'success') {
                    $('#editModal').hide();
                    fetchImages();
                } else {
                    alert('Update failed.');
                }
            })
            .fail(function () {
                alert('An error occurred during update.');
            });
    });

    // AJAX Delete
    $(document).on('click', '.delete-btn', function () {
        if (confirm('Are you sure?')) {
            const id = $(this).data('id');
            $.post({
                url: 'actions.php',
                data: { action: 'delete', id: id },
                dataType: 'json'
            })
                .done(function (response) {
                    if (response.status === 'success') {
                        fetchImages();
                    } else {
                        alert('Delete failed.');
                    }
                })
                .fail(function () {
                    alert('An error occurred during delete.');
                });
        }
    });

    // Lightbox Functionality
    $(document).on('click', '.thumb-preview', function () {
        const src = $(this).attr('src');
        const title = $(this).closest('tr').find('td:nth-child(2)').text();
        $('#lightboxImage').attr('src', src);
        $('#lightboxCaption').text(title);
        $('#lightboxModal').css('display', 'flex');
    });

    // Close lightbox on click of close button or background
    $(document).on('click', '.close-lightbox, #lightboxModal', function (e) {
        if (e.target !== this && !$(e.target).hasClass('close-lightbox')) return;
        $('#lightboxModal').hide();
    });
});