@extends('client.layouts.master')

@section('content')

<main class="main">
    <div class="wrap">


        <div class="row">
            <!--three-fourth content-->
            <div class="three-fourth">
                <h1>Liên hệ</h1>
                <!--map-->
                <div class="map-wrap">
                    <iframe
                            src="https://www.google.com/maps?q=21.0380208,105.7471299&hl=vi&z=15&output=embed"

                            width="100%"
                            height="700"
                            frameborder="0"
                            style="border:0;"
                            allowfullscreen>
                    </iframe>
                </div>
                <!--//map-->
            </div>

            <!--three-fourth content-->

            <!--sidebar-->
            <aside class="one-fourth right-sidebar lower">
                <!--form liên hệ-->
                <article class="widget">
                    <h4>Gửi tin nhắn cho chúng tôi</h4>
                    <form method="post" action="" name="contactform" id="contactform">
                        <fieldset>
                            <div id="message"></div>
                            <div class="row">
                                <div class="f-item full-width">
                                    <label for="name">Họ và tên của bạn</label>
                                    <input type="text" id="name" name="name" value="" />
                                </div>
                                <div class="f-item full-width">
                                    <label for="email">Email của bạn</label>
                                    <input type="email" id="email" name="email" value="" />
                                </div>
                                <div class="f-item full-width">
                                    <label for="email">Số điện thoại của bạn</label>
                                    <input type="number" id="phone" name="phone" value="" />
                                </div>
                                <div class="f-item full-width">
                                    <label for="comments">Lời nhắn của bạn</label>
                                    <textarea id="comments" rows="10" cols="10"></textarea>
                                </div>
                                <div class="f-item full-width">
                                    <input type="submit" value="Gửi" id="submit" name="submit" class="gradient-button" />
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </article>
                <!--//form liên hệ-->

                <!--thông tin liên hệ-->
                <article class="widget">
                    <h4>Hoặc liên hệ trực tiếp với chúng tôi</h4>
                    <p class="number">Số điện thoại: 0819571681</p>
                    <p class="email"><a href="mailto:booking@luxhome.vn">booking@luxhome.vn</a></p>
                </article>
                <!--//thông tin liên hệ-->
            </aside>

            <!--//sidebar-->
        </div>
        <!--//main content-->
    </div>
    <!-- Modal cảm ơn -->
    <div id="thankYouModal" class="thank-you-modal" style="display: none;">
        <div class="thank-you-content">
            <h2>Cảm ơn bạn đã gửi liên hệ!</h2>
            <p>Chúng tôi sẽ liên hệ lại với bạn trong thời gian sớm nhất.</p>
            <button id="closeModal" class="close-modal">Đóng</button>
        </div>
    </div>



    <style>
        /* Mô-cúp cảm ơn */
        .thank-you-modal {
            display: none; /* Ẩn modal mặc định */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .thank-you-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        .thank-you-content h2 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #4CAF50;
        }

        .thank-you-content p {
            font-size: 16px;
            color: #555;
        }

        .close-modal {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .close-modal:hover {
            background-color: #45a049;
        }
    </style>

</main>

@endsection

@section('style-libs')
    <link rel="stylesheet" href="{{asset('theme/client/css/lightslider.min.css')}}"/>
@endsection

@section('script-libs')
    <script type="text/javascript" src="{{asset('theme/client/js/lightslider.min.js')}}"></script>
    <script>
        // Lắng nghe sự kiện khi form được gửi
        document.getElementById('contactform').addEventListener('submit', function(e) {
            e.preventDefault();  // Ngăn form gửi đi

            // Giả lập gửi thành công (thay thế bằng logic gửi email thực tế)
            setTimeout(function() {
                // Đảm bảo modal luôn hiển thị mỗi lần form được gửi
                const modal = document.getElementById('thankYouModal');
                modal.style.display = 'flex';  // Hiển thị lại modal
            }, 1000);
        });

        // Đóng modal khi nhấn nút đóng
        document.getElementById('closeModal').addEventListener('click', function() {
            // Ẩn modal khi đóng
            const modal = document.getElementById('thankYouModal');
            modal.style.display = 'none';
        });


    </script>
@endsection