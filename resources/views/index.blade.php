@php
    use App\Models\Message;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat realtime</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @vite('resources/js/app.js')
</head>

<body>
    <main>
        <!-- component -->
        <!-- This is an example component -->
        <div class="container mx-auto shadow-lg  mt-[50px] border-1 border-gray">
            <!-- headaer -->
            <div class="px-5 py-5 flex justify-between items-center bg-white border-b-2">
                <div class="font-semibold text-2xl">
                    {{ auth()->user()->name ?? '' }}</div>
                <div class="w-1/2">
                    <input type="text" name="" id="" placeholder="search IRL"
                        class="rounded-2xl bg-gray-100 py-3 px-5 w-full" />
                </div>
                <a class=" h-12 w-12 p-2 bg-yellow-500 rounded-full text-white font-semibold flex items-center justify-center"
                    href="{{ route('logout') }}" title="Logout">
                    Out
                </a>
            </div>
            <!-- end header -->
            <!-- Chatting -->
            <div class="flex flex-row justify-between bg-white">
                <!-- chat list -->
                <div class="flex flex-col w-2/5 border-r-2 overflow-y-auto">
                    <!-- search compt -->
                    <div class="border-b-2 py-4 px-2">
                        <input type="text" placeholder="search chatting"
                            class="py-2 px-2 border-2 border-gray-200 rounded-2xl w-full" />
                    </div>
                    <!-- end search compt -->
                    <!-- user list -->
                    @foreach ($users as $user)
                        <a href="{{ route('home', [
                            'other_id' => $user->id,
                        ]) }}"
                            class="flex align-middle gap-2 m-2 border-1 border-gray-200 rounded-2xl p-1 {{ request()->other_id == $user->id ? 'bg-violet-300' : '' }}">
                            <img src="{{ $user->avatar }}" class="h-[30px] w-[30px] rounded-full" alt="">
                            <p>{{ $user->name }}</p>
                        </a>
                    @endforeach
                    <!-- end user list -->
                </div>
                <!-- end chat list -->
                <!-- message -->
                <div class="w-full px-5 flex flex-col justify-between">
                    <div class="flex flex-col mt-5 h-[1000px] overflow-auto" id="message-list">
                        {{-- list   --}}
                        @isset($otherUser)
                            @forelse ($messages as $message)
                                @if ($message->sender == auth()->user()->id)
                                    <div class="flex justify-end mb-[40px] reply-message relative ">
                                        <p class="text-black mr-[10px]">{{ $message->created_at }}</p>
                                        <div
                                            class="mr-2 py-3 px-4 bg-blue-400 rounded-bl-3xl rounded-tl-3xl rounded-tr-xl text-white">
                                            <p class="pb-[10px]">{{ $message->message }}</p>

                                            @switch($message->media_type)
                                                @case(Message::IMAGE)
                                                    @foreach ($message->mediaMessages as $media)
                                                        <img src="{{ $media->media_path }}" alt=""
                                                            class="rounded-2xl w-[400px]  mb-2" loading="lazy">
                                                    @endforeach
                                                @break

                                                @case(Message::VIDEO)
                                                @break

                                                @default
                                            @endswitch

                                        </div>
                                        <img src="{{ $message->user->avatar }}" class="object-cover h-8 w-8 rounded-full"
                                            alt="" />
                                        <p class="text-sm absolute top-[100%] right-[38px]">{!! $message->reply_id ? '<strong>Reply to:</strong> ' . $message->reply->message : '' !!}</p>
                                    </div>
                                @else
                                    <div class="flex justify-start mb-[40px] message-box relative">

                                        <img src="{{ $message->user->avatar }}" class="object-cover h-8 w-8 rounded-full"
                                            alt="" />

                                        <div
                                            class="ml-2 py-3 px-4 bg-gray-400 rounded-br-3xl rounded-tr-3xl rounded-tl-xl text-white group relative ">
                                            <p class="message-content">{{ $message->message }}</p>
                                            @switch($message->media_type)
                                                @case(Message::IMAGE)
                                                    @foreach ($message->mediaMessages as $media)
                                                        <img src="{{ $media->media_path }}" alt=""
                                                            class="rounded-2xl w-[400px] object-cover  mb-2" loading="lazy">
                                                    @endforeach
                                                @break

                                                @case(Message::VIDEO)
                                                @break

                                                @default
                                            @endswitch
                                            <div class="hidden group-hover:block absolute left-[100%] top-0 bg-blue-200 rounded-br-3xl rounded-tr-3xl rounded-tl-xl text-black cursor-pointer p-1 px-3 text-sm button-reply"
                                                data-reply-id="{{ $message->id }}">
                                                <p>Reply</p>
                                            </div>

                                        </div>
                                        <p class="text-black ml-[10px]">{{ $message->created_at }}</p>
                                        <p class="text-sm absolute top-[100%] left-[38px]">{!! $message->reply_id ? '<strong>Reply to:</strong> ' . $message->reply->message : '' !!}</p>
                                    </div>
                                @endif


                                @empty
                                @endforelse

                            @endisset

                        </div>
                        <form class=" flex py-5 gap-2 relative">
                            <div
                                class="absolute p-1 px-2 w-full h-[50px] bg-amber-100 rounded-xl top-0 translate-y-[-100%] hidden">
                                <span class="font-bold">Reply: </span> <span id="reply-to"></span>
                                <button
                                    class="absolute top-1 h-8 w-8 right-1 hover:rotate-180 transition-all  rounded-b-md cursor-pointer"
                                    type="button" id="cancel-reply">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                        viewBox="0 0 32 32">
                                        <path fill="currentColor"
                                            d="M16 3C8.832 3 3 8.832 3 16s5.832 13 13 13s13-5.832 13-13S23.168 3 16 3m0 2c6.087 0 11 4.913 11 11s-4.913 11-11 11S5 22.087 5 16S9.913 5 16 5m-3.78 5.78l-1.44 1.44L14.564 16l-3.782 3.78l1.44 1.44L16 17.437l3.78 3.78l1.44-1.437L17.437 16l3.78-3.78l-1.437-1.44L16 14.564l-3.78-3.782z" />
                                    </svg>
                                </button>
                            </div>

                            <input type="text" hidden name="reply-id" value="">
                            <input type="text" name="receiver" value="{{ request()->other_id }}" hidden>
                            <input class="w-full border-1 border-gray-200 py-5 px-3 rounded-xl" type="text"
                                placeholder="Type your message here..." id="message" />
                            <input type="file" id="file" name="file" accept="image/*, video/*" hidden multiple>
                            <label
                                class="flex items-center justify-center bg-purple-400 cursor-pointer px-4 rounded-2xl hover:bg-purple-500"
                                name="add-file-button" for="file">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path fill="currentColor" fill-rule="evenodd"
                                        d="M15.288 3.706c-1.03-.14-2.38-.14-4.29-.14h-.25v.3c0 1.37 0 2.47-.12 3.34c-.12.9-.38 1.66-.98 2.26s-1.36.86-2.26.98c-.87.12-1.97.12-3.34.12h-.3v4.26c0 1.9 0 3.26.14 4.28c.14 1 .39 1.58.81 2.01s1 .68 2.01.82c1.03.14 2.38.14 4.28.14c.41 0 .75.34.75.75s-.34.75-.75.75h-.06c-1.83 0-3.29 0-4.42-.15c-1.17-.16-2.12-.49-2.87-1.23c-.75-.76-1.08-1.7-1.23-2.88c-.15-1.14-.15-2.59-.15-4.43v-5.06c0-.21.08-.4.22-.55l6.94-6.92c.14-.18.35-.29.59-.29h1.06c1.84 0 3.29 0 4.43.15c1.17.16 2.12.49 2.87 1.24s1.08 1.7 1.24 2.87c.15 1.14.15 2.6.15 4.43v1.06c0 .41-.34.75-.75.75s-.75-.34-.75-.75v-1c0-1.91 0-3.26-.14-4.29c-.14-1.01-.39-1.59-.81-2.01s-1-.68-2.01-.81zm-10.49 5.36l4.45-4.43c0 1.01-.02 1.77-.1 2.38c-.1.73-.28 1.12-.56 1.4s-.66.46-1.4.56c-.61.08-1.37.1-2.39.1zm16.07 11.04c-1.12.44-2.14 1.46-2.58 2.58c-.46 1.17-2.12 1.17-2.58 0c-.44-1.12-1.46-2.14-2.58-2.58c-1.17-.46-1.17-2.12 0-2.58c1.12-.44 2.14-1.46 2.58-2.58c.46-1.17 2.12-1.17 2.58 0c.44 1.12 1.46 2.14 2.58 2.58c1.17.46 1.17 2.12 0 2.58m-6.94-1.29c1.32.6 2.47 1.75 3.07 3.07c.6-1.32 1.75-2.48 3.07-3.07a6.36 6.36 0 0 1-3.07-3.07c-.6 1.32-1.75 2.48-3.07 3.07"
                                        color="currentColor" />
                                </svg>
                            </label>
                            <button type="button" id="send"
                                class="bg-amber-400 cursor-pointer px-4 rounded-2xl hover:bg-amber-500">Send</button>
                        </form>
                        <div class="mb-[20px]" id="preview-file">
                        </div>
                    </div>
                    <!-- end message -->
                    <div class="w-2/5 border-l-2 px-5">
                        <div class="flex flex-col">
                            @isset($otherUser)
                                <div class="font-semibold text-xl py-4">{{ $otherUser->name }}</div>
                                <img src="{{ $otherUser->avatar }}" alt="" />
                                <div class="font-semibold py-4">{{ $otherUser->email }}</div>
                                <div class="font-light">
                                    Mấy con chó chỉ mong tao sống nhiều khó khăn
                                </div>
                            @endisset

                        </div>
                    </div>
                </div>
            </div>
            </div>
        </main>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.socket.io/4.8.1/socket.io.min.js"></script>
        @isset($otherUser)
            <script>
                const spinSvg = `
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M12 6.99998C9.1747 6.99987 6.99997 9.24998 7 12C7.00003 14.55 9.02119 17 12 17C14.7712 17 17 14.75 17 12"><animateTransform attributeName="transform" attributeType="XML" dur="560ms" from="0,12,12" repeatCount="indefinite" to="360,12,12" type="rotate"/></path></svg>
                    `
                const otherMessage = data => `
        <div class="flex mb-[30px] justify-start mb-4 message-box relative">
            <img src="{{ $otherUser->avatar }}"
                class="object-cover h-8 w-8 rounded-full" alt="" />
            <div class="ml-2 py-3 px-4 bg-gray-400 rounded-br-3xl rounded-tr-3xl rounded-tl-xl text-white group relative ">
                <p class="message-content">${data.message??""}</p>
                ${data.media_type == 1 ? data.media_messages.map(e => ("<img src="+ e.media_path+` class="w-[400px] rounded-2xl object-cover mb-2" /> `))  : ""}
                <div class="hidden group-hover:block absolute left-[100%] top-0 bg-blue-200 rounded-br-3xl rounded-tr-3xl rounded-tl-xl text-black cursor-pointer p-1 px-3 text-sm button-reply" 
                    data-reply-id="${data.id}">
                    <p>Reply</p>
                </div>
            </div>
            <p class="text-black">${data.created_at.replace('T', ' ').slice(0, 19)}</p>
            <p class="text-sm absolute top-[100%] left-[38px]">${data.replyMessage ? "<strong>Reply to:</strong> " + data.replyMessage : ""}</p>
        </div>
    `;

                const myMessage = ({
                    message,
                    replyMessage
                }) => {
                    console.log(message);

                    return `
        <div class="flex mb-[30px] justify-end mb-4 reply-message relative ">
            <p>{{ Carbon\Carbon::now() }}</p>
            <div class="mr-2 py-3 px-4 bg-blue-400 rounded-bl-3xl rounded-tl-3xl rounded-tr-xl text-white">
                <p>${message.message ?? ''}</p>
                ${message.media_type == 1 ? message.media_messages.map(e => ("<img src="+ e.media_path+` class="w-[400px]  rounded-2xl object-cover  mb-2" /> `))  : ""}
            </div>
            <img src="{{ auth()->user()->avatar }}"
                class="object-cover h-8 w-8 rounded-full" alt="" />
            <p class="text-sm absolute top-[100%] right-[38px]">${message.replyMessage ? "<strong>Reply to:</strong> " + message.replyMessage : ""}</p>
        </div>
    `;
                }
                // ${data.message.media_type == 1 ? "<img src="+ data.message.media.media_path+" /> " : ""}
                $(document).ready(() => {
                    const socket = io('http://localhost:3000');
                    const sendBtn = $('#send');
                    const messageInput = $('#message');
                    const messageList = $('#message-list');
                    const replyTo = $('#reply-to');
                    const replyIdInput = $('input[name="reply-id"]');
                    const receiverInput = $('input[name="receiver"]');
                    const fileMessage = $('#file');
                    const preview = document.getElementById('preview-file');

                    messageInput.keydown(event => {
                        if (event.key === 'Enter') {
                            if (!messageInput.val() && !fileMessage.val()) return false;
                            sendBtn.click();
                        }
                    });

                    sendBtn.click(() => {

                        if (!messageInput.val() && !fileMessage.val()) return;

                        // messageList.append(myMessage({
                        //     message: messageInput.val(),
                        //     replyMessage: replyTo.text() || ""
                        // }));

                        $('#send').html(spinSvg);
                        $('#send').attr('disabled', true)

                        const formData = new FormData();
                        formData.append('receiver_id', receiverInput.val());
                        formData.append('message', messageInput.val());
                        formData.append('reply_id', replyIdInput.val());

                        if (fileMessage[0].files.length > 0) {
                            for (let i = 0; i < fileMessage[0].files.length; i++) {
                                formData.append('file[]', fileMessage[0].files[i]);
                            }
                        }


                        $.ajax({
                            type: "POST",
                            url: "{{ route('send_messages') }}",
                            cache: false,
                            contentType: false,
                            processData: false,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            data: formData,

                            success: function(response) {
                                $('#send').text('Send');
                                $('#send').removeAttr('disabled');
                                console.log(response);

                                messageList.append(myMessage({
                                    message: response.message,
                                    replyMessage: response.message.reply_id ? response
                                        .message.reply.message : ""
                                }));
                                socket.emit('private-send', {
                                    sendUserId: '{{ auth()->user()->id }}',
                                    recieveUserId: receiverInput.val(),
                                    message: response.message,
                                });
                                messageInput.val('');
                                replyIdInput.val('');
                                replyTo.text('');
                                replyTo.parent().hide();
                                fileMessage.val('');
                                scrollMessageToBottom();

                            }
                        });
                        preview.innerHTML = ''
                    });

                    socket.on(`message_from_${receiverInput.val()}_to_{{ auth()->user()->id }}`, data => {
                        const createMessageElement = otherMessage(data.message);
                        messageList.append(createMessageElement);
                        scrollMessageToBottom();
                    });

                    const scrollMessageToBottom = () => {
                        messageList[0].scrollTop = messageList[0].scrollHeight;
                        messageList.find('img[loading="lazy"]').each(function() {
                            this.addEventListener('load', function() {
                                messageList[0].scrollTop = messageList[0].scrollHeight;
                            });
                        });
                    };
                    scrollMessageToBottom();
                });

                $(document).on('click', '.button-reply', function() {
                    const replyId = $(this).data('reply-id');
                    $('input[name="reply-id"]').val(replyId);
                    const messageContent = $(this).closest('.message-box').find('.message-content').text();
                    $('#reply-to').text(messageContent);
                    $('#reply-to').parent().show();
                    $('#message').focus();
                });

                $(document).on('click', '#cancel-reply', function() {
                    $('input[name="reply-id"]').val('');
                    $('#reply-to').text('');
                    $('#reply-to').parent().hide();
                });

                document.getElementById('file').addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    const preview = document.getElementById('preview-file');
                    if (file) {
                        const reader = new FileReader();
                        preview.innerHTML = spinSvg;
                        reader.onload = function(e) {
                            if (file.type.startsWith('video/')) {
                                // Replace preview with a video element
                                preview.innerHTML =
                                    `<video class="m-h-20 m-w-20" controls src="${e.target.result}"></video>`;
                            } else if (file.type.startsWith('image/')) {
                                // Show image preview
                                preview.innerHTML = `<img class="m-h-20 m-w-20" src="${e.target.result}" alt="">`;
                            } else {
                                preview.innerHTML = `<span>File type not supported for preview</span>`;
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
            </script>
        @endisset


        {{-- <script>
    const spinSvg = `
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M12 6.99998C9.1747 6.99987 6.99997 9.24998 7 12C7.00003 14.55 9.02119 17 12 17C14.7712 17 17 14.75 17 12"><animateTransform attributeName="transform" attributeType="XML" dur="560ms" from="0,12,12" repeatCount="indefinite" to="360,12,12" type="rotate"/></path></svg>
    `;
    
    function otherMessage(data) {
        return `
            <div class="flex mb-[30px] justify-start mb-4 message-box relative">
                <img src="{{ $otherUser->avatar }}" class="object-cover h-8 w-8 rounded-full" alt="" />
                <div class="ml-2 py-3 px-4 bg-gray-400 rounded-br-3xl rounded-tr-3xl rounded-tl-xl text-white group relative ">
                    <p class="message-content">${data.message ?? ""}</p>
                    ${data.media_type == 1 ? data.media_messages.map(e => ("<img src="+ e.media_path+` class="w-[400px] rounded-2xl object-cover mb-2" /> ")).join('') : ""}
                    <div class="hidden group-hover:block absolute left-[100%] top-0 bg-blue-200 rounded-br-3xl rounded-tr-3xl rounded-tl-xl text-black cursor-pointer p-1 px-3 text-sm button-reply" data-reply-id="${data.id}">
                        <p>Reply</p>
                    </div>
                </div>
                <p class="text-black">${data.created_at.replace('T', ' ').slice(0, 19)}</p>
                <p class="text-sm absolute top-[100%] left-[38px]">${data.replyMessage ? "<strong>Reply to:</strong> " + data.replyMessage : ""}</p>
            </div>
        `;
    }
    
    function myMessage({ message, replyMessage }) {
        return `
            <div class="flex mb-[30px] justify-end mb-4 reply-message relative ">
                <p>{{ Carbon\\Carbon::now() }}</p>
                <div class="mr-2 py-3 px-4 bg-blue-400 rounded-bl-3xl rounded-tl-3xl rounded-tr-xl text-white">
                    <p>${message.message ?? ''}</p>
                    ${message.media_type == 1 ? message.media_messages.map(e => ("<img src="+ e.media_path+` class="w-[400px]  rounded-2xl object-cover  mb-2" /> ")).join('') : ""}
                </div>
                <img src="{{ auth()->user()->avatar }}" class="object-cover h-8 w-8 rounded-full" alt="" />
                <p class="text-sm absolute top-[100%] right-[38px]">${message.replyMessage ? "<strong>Reply to:</strong> " + message.replyMessage : ""}</p>
            </div>
        `;
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const socket = io('http://localhost:3000');
        const sendBtn = document.getElementById('send');
        const messageInput = document.getElementById('message');
        const messageList = document.getElementById('message-list');
        const replyTo = document.getElementById('reply-to');
        const replyIdInput = document.querySelector('input[name="reply-id"]');
        const receiverInput = document.querySelector('input[name="receiver"]');
        const fileMessage = document.getElementById('file');
        const preview = document.getElementById('preview-file');
    
        messageInput.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                if (!messageInput.value && !fileMessage.value) return false;
                sendBtn.click();
            }
        });
    
        sendBtn.addEventListener('click', function() {
            if (!messageInput.value && !fileMessage.value) return;
            sendBtn.innerHTML = spinSvg;
            sendBtn.setAttribute('disabled', true);
    
            const formData = new FormData();
            formData.append('receiver_id', receiverInput.value);
            formData.append('message', messageInput.value);
            formData.append('reply_id', replyIdInput.value);
            if (fileMessage.files.length > 0) {
                for (let i = 0; i < fileMessage.files.length; i++) {
                    formData.append('file[]', fileMessage.files[i]);
                }
            }
    
            fetch("{{ route('send_messages') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(response => {
                sendBtn.textContent = 'Send';
                sendBtn.removeAttribute('disabled');
                messageList.insertAdjacentHTML('beforeend', myMessage({
                    message: response.message,
                    replyMessage: response.message.reply_id ? response.message.reply.message : ""
                }));
                socket.emit('private-send', {
                    sendUserId: '{{ auth()->user()->id }}',
                    recieveUserId: receiverInput.value,
                    message: response.message,
                });
                messageInput.value = '';
                replyIdInput.value = '';
                replyTo.textContent = '';
                replyTo.parentElement.style.display = 'none';
                fileMessage.value = '';
                scrollMessageToBottom();
                preview.innerHTML = '';
            });
        });
    
        socket.on(`message_from_${receiverInput.value}_to_{{ auth()->user()->id }}`, function(data) {
            messageList.insertAdjacentHTML('beforeend', otherMessage(data.message));
            scrollMessageToBottom();
        });
    
        function scrollMessageToBottom() {
            messageList.scrollTop = messageList.scrollHeight;
            messageList.querySelectorAll('img[loading="lazy"]').forEach(function(img) {
                img.addEventListener('load', function() {
                    messageList.scrollTop = messageList.scrollHeight;
                });
            });
        }
        scrollMessageToBottom();
    
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('button-reply')) {
                const replyId = e.target.getAttribute('data-reply-id');
                replyIdInput.value = replyId;
                const messageContent = e.target.closest('.message-box').querySelector('.message-content').textContent;
                replyTo.textContent = messageContent;
                replyTo.parentElement.style.display = '';
                messageInput.focus();
            }
            if (e.target.id === 'cancel-reply') {
                replyIdInput.value = '';
                replyTo.textContent = '';
                replyTo.parentElement.style.display = 'none';
            }
        });
    
        fileMessage.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                preview.innerHTML = spinSvg;
                reader.onload = function(e) {
                    if (file.type.startsWith('video/')) {
                        preview.innerHTML = `<video class="m-h-20 m-w-20" controls src="${e.target.result}"></video>`;
                    } else if (file.type.startsWith('image/')) {
                        preview.innerHTML = `<img class="m-h-20 m-w-20" src="${e.target.result}" alt="">`;
                    } else {
                        preview.innerHTML = `<span>File type not supported for preview</span>`;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    });
    </script> --}}
    </body>

    </html>
