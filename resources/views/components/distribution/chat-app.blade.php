
@push('head')
<link rel="stylesheet" href="{{asset('css/chatbox.css')}}">
<script src="https://unpkg.com/timeago.js/dist/timeago.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endpush
<div class="chat-app-container flex flex-column">
    <section class="chat-heading flex">
        <div class="chat-title-section flex flex-column">
            <h4 class="convo-title text-bold">Distribution</h4>
            <div class="convo-info">
                {{$item_description}}
            </div>
        </div>
        <div class="message-counter">
            
        </div>
    </section>
    <div class="attached-image-wrapper hide">
        <img class="attached-image" src="" alt="attached-image">
        <div class="image-attachment-buttons">
            <button class="cancel-image-send btn btn-danger" type="button">Cancel</button>
        </div>
    </div>
    <div class="scroll-body">
        <section class="chat-body flex flex-column">
            <div class="no-message-note"><h4 class="text-center">💬 No comments to show...</h4><div>
        </section>
    </div>
    @if ($to_comment)
    <section class="type-message-section">
        <form action="" class="message-form flex">
            <input type="text" name="returns_header_id" class="hide" value="{{ $new_items_id }}">
            <input type="text" name="table" class="hide" value="{{ $table }}">
            <textarea type="text" name="comment_content" class="type-message form-control" placeholder="✏️ Type your comment here..." ></textarea>
            <div class="flex flex-column">
                <input class="image-input hide" name="attached_image" type="file" id="image" name="image" accept="image/*">
                <button class="btn btn-info attach-img-btn" type="button">🖼️Attach image</button>
                <button class="btn btn-success send-btn" type="submit">➡️Send</button>
            </div>
        </form>
    </section>
    @endif
</div> 

@push('bottom')
<script>
    const savedComments =  {!! json_encode($comments) !!}

    function reloadInfo() {
        const messageCount = $('.message').length;
        $('.message-counter').text(`${messageCount} Comment(s)`);
        if (messageCount) {
            $('.no-message-note').hide();
        } else {
            $('.no-message-note').show();
            return;
        }
        timeago.cancel();
        const nodes = $('.timeago').get();
        timeago.render(nodes);
        $('.attached-image-wrapper').addClass('hide');
        $('.attached-image').attr('href', '');
        $('.image-input').val('');
    }

    function appendComment(comments) {
        const myId = "{{ CRUDBooster::myId() }}";
        const scrollBody = $('.scroll-body');
        comments.forEach(comment => {
            let sender = comment.name?.trim();
            if (!sender || !sender.length) {
                sender = 'Unknown Sender';
            }
            const splittedBySpace = sender.split(' ');
            const initials = splittedBySpace[0][0] + splittedBySpace.at(-1)[0];
            const messageContainer = $(document.createElement('div')).addClass('flex');
            const profilePhotoSection = $(document.createElement('div')).addClass('profile-photo-section flex');
            const profilePhoto = $(document.createElement('div')).addClass('profile-photo').text(initials);
            const messageWrapper = $(document.createElement('div')).addClass('message-wrapper flex flex-column');
            const senderDetails = $(document.createElement('div')).addClass('sender-details flex');
            const senderName = $(document.createElement('div')).addClass('sender-name text-bold');
            const senderDate = $(document.createElement('div')).attr('datetime', comment.comment_added_at).addClass('sender-date timeago');
            const message = $(document.createElement('div')).addClass('message').text(comment.message);
            // const deleteComment = $(document.createElement('div')).addClass('delete-comment').text('×').hide();

            if (comment.file_name) {
                const img = $(document.createElement('img'))
                    .attr('src', `{{ asset('chat_img') }}/${comment.file_name}`)
                    .attr('data-action', 'zoom')
                    .addClass('message-image');
                message.append(img)
            }

            const isMyMessage = comment.cms_users_id == myId;

            if (isMyMessage) {
                messageWrapper.addClass('my-message-wrapper');
                messageContainer.addClass('my-message-container');
                senderDetails.addClass('my-sender-details');
                message.addClass('my-message');
                senderName.text('Me');

            } else {
                messageWrapper.addClass('their-message-wrapper');
                messageContainer.addClass('their-message-container');
                message.addClass('their-message');
                profilePhotoSection.append(profilePhoto);
                messageContainer.append(profilePhotoSection);
                senderName.text(sender);
            }
            message.attr('comment_id', comment.comment_id);
            senderDetails.append(senderName, senderDate);
            messageWrapper.append(senderDetails, message);
            messageContainer.append(messageWrapper);

            $('.chat-body').append(messageContainer);
        });
        scrollBody.ready(function() {
            scrollBody.animate({scrollTop: scrollBody.prop('scrollHeight')}, 1000)
            reloadInfo();
        });
    }

    function formatMyMessage() {
        $('.my-message').each(function() {
            if (!$(this).attr('_clicked')) {
                $(this).find('.delete-comment').hide();
            }
        })
    }

    $('.type-message').on('keyup', function() {
        const value = $(this).val().trim()
        if (value) $('.send-btn').attr('disabled', false);
    });

    $('.message-form').on('submit', function(event) {
        event.preventDefault();
        const message = $('.type-message').val().trim();
        const image = $('.image-input').val();
        const newItemsId = "{{ $new_items_id }}";
        if (!message && !image) {
            $('.send-btn').attr('disabled', true);
            return;
        }
        const form = $('.message-form').get();
        const formData = new FormData(form[0]);
        Swal.fire({
            title: 'Sending comment...',
            html: 'Please wait...',
            allowEscapeKey: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            },
        });
        $.ajax({
            type: 'POST',
            url: "{{ route('send_msg_distri') }}",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.close();
                response = JSON.parse(response);
                appendComment(response);
                console.log(response);
            },
            error: function(response) { 
                console.log(response);
                Swal.fire({
                    title: 'Oops',
                    html: 'Something went wrong.',
                    icon: 'error'
                });
            }  
        });
        $('.type-message').val('');
    });

    $(document).on('click', '.my-message', function() {
        if ($(this).attr('_clicked')) {
            $('.my-message').removeAttr('_clicked');
        } else {
            $('.my-message').removeAttr('_clicked');
            $(this).attr('_clicked', 'true');
        }
        formatMyMessage();
    });

    $(document).on('click', '.attach-img-btn', function() {
        $('.image-input').click();
    });

    $(document).on('change', '.image-input', function(event) {
        const selectedFile = event.target.files[0];
        if (selectedFile) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('.attached-image').attr('src', e.target.result);
            };
            reader.readAsDataURL(selectedFile);
            const currentValue = $(this).val();
            const filename = currentValue.split("\\").at(-1);
            $('.attached-image-wrapper').removeClass('hide');
            $('.send-btn').attr('disabled', false);
        }

    });

    $('.cancel-image-send').on('click', function() {
        $('.attached-image-wrapper').addClass('hide');
        $('.attached-image').attr('href', '');
        $('.image-input').val('');
    });

    @if ($to_comment)
    $(document).on('click', '.delete-comment', function() {
        const messageId = $(this).parents('.message').attr('comment_id');
        Swal.fire({
            title: 'Are you sure you want to delete this comment?',
            html: '📄 You won\'t be able to revert this.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteInDB(messageId);
            }
        });
    });
    @endif

    appendComment(savedComments);
</script>
@endpush