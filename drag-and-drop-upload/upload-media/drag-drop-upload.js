jQuery(document).ready(function($) {
    $('.ddu-dropzone').each(function() {
        const dropzone = $(this);
        const fileInput = dropzone.find('.ddu-file-input');
        const progress = dropzone.closest('.ddu-upload-container').find('.ddu-progress');
        const progressBar = progress.find('.ddu-progress-bar');
        const results = dropzone.closest('.ddu-upload-container').find('.ddu-results');
        
        // ✅ Use existing label or wrap content safely (don't move file input)
        const content = dropzone.find('.ddu-dropzone-content');
        if (!content.parent().hasClass('ddu-dropzone-label')) {
            content.wrap('<label class="ddu-dropzone-label"></label>');
        }

        // ✅ Don't move fileInput if it's already in the right place
        if (!fileInput.closest('label').length) {
            dropzone.find('.ddu-dropzone-label').prepend(fileInput);
        }

        // File input change handler
        fileInput.off('change').on('change', function(e) {
            if (this.files.length) {
                handleFiles(this.files);
            }
        });

        // Drag and drop handlers
        dropzone.on('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropzone.addClass('ddu-dragover');
        });

        dropzone.on('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropzone.removeClass('ddu-dragover');
        });

        dropzone.on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropzone.removeClass('ddu-dragover');

            if (e.originalEvent.dataTransfer.files.length) {
                handleFiles(e.originalEvent.dataTransfer.files);
            }
        });

        // Handle file upload
        function handleFiles(files) {
            const formData = new FormData();
            formData.append('file', files[0]);
            formData.append('action', 'drag_drop_upload');
            formData.append('nonce', ddu_vars.nonce);

            progress.show();
            progressBar.css('width', '0%');

            $.ajax({
                url: ddu_vars.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function() {
                    const xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            const percent = Math.round((e.loaded / e.total) * 100);
                            progressBar.css('width', percent + '%');
                        }
                    });
                    return xhr;
                },
                success: function(response) {
                    if (response.success) {
                        results.html(`
                            <div class="ddu-success">
                                <p>File uploaded successfully!</p>
                                <p>Attachment ID: ${response.data.id}</p>
                                <p>URL: <a href="${response.data.url}" target="_blank">${response.data.url}</a></p>
                                ${response.data.thumbnail ? `<img src="${response.data.thumbnail}" alt="Thumbnail">` : ''}
                            </div>
                        `);
                    } else {
                        results.html(`<div class="ddu-error">Error: ${response.data}</div>`);
                    }
                },
                error: function(xhr) {
                    results.html(`<div class="ddu-error">Upload failed: ${xhr.responseText}</div>`);
                },
                complete: function() {
                    progress.hide();
                    fileInput.val('');
                }
            });
        }
    });
});
