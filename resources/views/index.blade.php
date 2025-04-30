<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat realtime</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

</head>

<body>
    <main>
        <!-- component -->
        <!-- This is an example component -->
        <div class="container mx-auto shadow-lg  mt-[50px] border-1 border-gray">
            <!-- headaer -->
            <div class="px-5 py-5 flex justify-between items-center bg-white border-b-2">
                <div class="font-semibold text-2xl">{{auth()->user()->name ?? ''}}</div>
                <div class="w-1/2">
                    <input type="text" name="" id="" placeholder="search IRL"
                        class="rounded-2xl bg-gray-100 py-3 px-5 w-full" />
                </div>
                <div
                    class="h-12 w-12 p-2 bg-yellow-500 rounded-full text-white font-semibold flex items-center justify-center">
                    RA
                </div>
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
                    
                    <!-- end user list -->
                </div>
                <!-- end chat list -->
                <!-- message -->
                <div class="w-full px-5 flex flex-col justify-between">
                    <div class="flex flex-col mt-5" id="message-list">
                        
                        
                    </div>
                    <form class=" flex py-5 gap-2 relative">
                        <div class="absolute p-1 px-2 w-full h-[50px] bg-amber-100 rounded-xl top-0 translate-y-[-100%] hidden" >
                            <span class="font-bold">Reply: </span> <span id="reply-to"></span>
                            <button class="absolute top-0 right-0 p-1 bg-red-500 rounded-b-md cursor-pointer" type="button" id="cancel-reply"> x </button>
                        </div>
                        <input type="text" hidden name="reply-id" value="">
                        <input class="w-full bg-gray-300 py-5 px-3 rounded-xl" type="text"
                            placeholder="type your message here..." id="message" />
                        <button type="button"  id="sends" class="bg-amber-400 cursor-pointer px-4 rounded-2xl hover:bg-amber-500">Send</button>
                    </form>
                </div>
                <!-- end message -->
                <div class="w-2/5 border-l-2 px-5">
                    <div class="flex flex-col">
                        <div class="font-semibold text-xl py-4">Mern Stack Group</div>
                        <img  src="https://scontent.fvii2-1.fna.fbcdn.net/v/t39.30808-6/494460267_2411894892506563_7029212052190670471_n.jpg?stp=dst-jpg_s640x640_tt6&_nc_cat=100&ccb=1-7&_nc_sid=aa7b47&_nc_ohc=gUTmI7PNJHQQ7kNvwE-BWVL&_nc_oc=AdlHCYA1Qz-auWrYORSYiIsD7g8Wt5vp4HTR7DBByPK3y6Ne773JrBFzh8_IetKdbv0&_nc_zt=23&_nc_ht=scontent.fvii2-1.fna&_nc_gid=bhz98jMdjqWj1c8W5SUSUA&oh=00_AfGnpmr7P6hhBnnSoFSPNb_616z5y4MX_23s5c8SF65Znw&oe=6817F1CD" class="object-cover rounded-xl h-64"
                            alt="" />
                        <div class="font-semibold py-4">Created 22 Sep 2021</div>
                        <div class="font-light">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt,
                            perspiciatis!
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.socket.io/4.8.1/socket.io.min.js"></script>
    <script>
            $(document).ready(() => {
                const data = {
                name: "Luis",
                message: "Hello"
                }
                let socket = io('http://localhost:3000')
                
                const sendBtn = $('#sends')
                const messageInput = $('#message')
                $('#message').keydown(function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        $('#sends').click();
                    }
                });
                sendBtn.click(() => {
                    $('#message-list').append(myMessage({message: messageInput.val(), replyMessage: $('#reply-to').text() ?? ""})) 
                    $.ajax({
                        type: "post",
                        url: "{{route('send_messages')}}",
                        data: {
                            receiver_id: '{{auth()->user()->id == 1? 2 : 1 }}',
                            message: messageInput.val(),
                            reply_id: $('input[name="reply-id"]').val(),
                            _token: '{{csrf_token()}}',
                        },
                        success: function (response) {
                            socket.emit('private-send', {
                                sendUserId: '{{auth()->user()->id}}',
                                recieveUserId: '{{auth()->user()->id == 1? 2 : 1 }}',
                                message: response.message,
                            })
                            messageInput.val('')
                            $('input[name="reply-id"]').val('')
                            $('#reply-to').text('')
                            $('#reply-to').parent().hide()
                        }
                    });
                    
                
                })
                const otherMessage = (data) => `
                                                    <div class="flex mb-[30px] justify-start mb-4 message-box relative">
                                                        
                                                        <img onerror="this.src='https://yt3.ggpht.com/VctzOyDyuNWOccIih-QVapNGtLfX7B8O9HaqFej8Lgu2BtTAUv2P7tG5OT-_6PdU-P6WzqtX8A=s48-c-k-c0x00ffffff-no-rj'" src="https://source.unsplash.com/vpOeXr5wmR4/600x600"
                                                            class="object-cover h-8 w-8 rounded-full" alt="" />
                                                        <div
                                                            class="ml-2 py-3 px-4 bg-gray-400 rounded-br-3xl rounded-tr-3xl rounded-tl-xl text-white group relative ">
                                                        <p class="message-content">${data.message}</p> 
                                                            <div class="group-hover:block absolute left-[110%] top-0 bg-blue-200 rounded-br-3xl rounded-tr-3xl rounded-tl-xl text-black cursor-pointer p-1 px-3 text-sm button-reply" 
                                                            data-reply-id="${data.id}">
                                                                <p>Reply</p> 
                                                            </div>
                                                            
                                                        </div>
                                                        <p class="text-sm absolute top-[100%] left-[38px]">${data.replyMessage ? "<strong>Reply to:</strong> " + data.replyMessage : ""}</p>
                                                    </div>
                                                `

                const myMessage = (data) => `
                                                <div class="flex mb-[30px] justify-end mb-4 reply-message relative ">
                                                    
                                                    <div
                                                        class="mr-2 py-3 px-4 bg-blue-400 rounded-bl-3xl rounded-tl-3xl rounded-tr-xl text-white">
                                                        ${data.message}
                                                    </div>
                                                    <img onerror="this.src='https://yt3.ggpht.com/VctzOyDyuNWOccIih-QVapNGtLfX7B8O9HaqFej8Lgu2BtTAUv2P7tG5OT-_6PdU-P6WzqtX8A=s48-c-k-c0x00ffffff-no-rj'" src="https://source.unsplash.com/vpOeXr5wmR4/600x600"
                                                        class="object-cover h-8 w-8 rounded-full" alt="" />
                                                    <p class="text-sm absolute top-[100%] right-[38px]">${data.replyMessage ? "<strong>Reply to:</strong> " + data.replyMessage : ""}</p>
                                                </div>
                                            `

                socket.on('message_from_{{auth()->user()->id == 1? 2 : 1 }}_to_{{auth()->user()->id}}', (data) => {
                    var createMessageElement = data.sendUserId == '{{auth()->user()->id}}'? myMessage(data.message) : otherMessage(data.message)
                    $('#message-list').append(createMessageElement) 
                })  
            })
            
          
          
    </script>
    <script>
        $(document).on('click', '.button-reply', function() {
            let replyId = $(this).data('reply-id');
            $('input[name="reply-id"]').val(replyId)
            // console.log(replyId);
            
            let messageContent =  $(this).closest('.message-box').find('.message-content').text();
            $('#reply-to').text(messageContent)
            $('#reply-to').parent().show();
            // $('#message').val(replyId)
        });
        $(document).on('click', '#cancel-reply', function() {
            $('input[name="reply-id"]').val('')
            $('#reply-to').text('')
            $('#reply-to').parent().hide();
        });
    </script>
</body>

</html>
