<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn Đặt Phòng Khách Sạn</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }

        .invoice-container {
            background-color: #fff;
            padding: 20px;
            margin: 0 auto;
            max-width: 600px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        header h1 {
            color: #333;
            font-size: 24px;
        }

        section {
            margin-bottom: 20px;
        }

        h2 {
            font-size: 18px;
            color: #555;
            margin-bottom: 10px;
        }

        p, table {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f8f8f8;
        }

        .price-info .total th, .price-info .total td {
            font-size: 18px;
            font-weight: bold;
        }

        footer {
            text-align: center;
            padding-top: 20px;
            font-size: 14px;
            color: #777;
        }

    </style>
</head>
<body>
<div class="invoice-container">
    <header>
        <h1>HÓA ĐƠN ĐẶT PHÒNG KHÁCH SẠN</h1>
        <p>Cảm ơn bạn đã đặt phòng tại khách sạn của chúng tôi!</p>
    </header>

    <section class="hotel-info">
        <h2>Thông tin khách sạn</h2>
        <p>Tên khách sạn: Khách Sạn ABC</p>
        <p>Địa chỉ: 123 Đường XYZ, Quận 1, TP.HCM</p>
        <p>Số điện thoại: 0123 456 789</p>
    </section>

    <section class="guest-info">
        <h2>Thông tin khách hàng</h2>
        <p>Tên khách hàng: Nguyễn Văn A</p>
        <p>Email: nguyenvana@gmail.com</p>
        <p>Số điện thoại: 0987 654 321</p>
    </section>

    <section class="booking-info">
        <h2>Thông tin đặt phòng</h2>
        <table>
            <tr>
                <th>Ngày nhận phòng:</th>
                <td>20/10/2024</td>
            </tr>
            <tr>
                <th>Ngày trả phòng:</th>
                <td>22/10/2024</td>
            </tr>
            <tr>
                <th>Số lượng phòng:</th>
                <td>1</td>
            </tr>
            <tr>
                <th>Loại phòng:</th>
                <td>Phòng Deluxe</td>
            </tr>
            <tr>
                <th>Tổng số đêm:</th>
                <td>2</td>
            </tr>
        </table>
    </section>

    <section class="extra-fee-info">
        <h2>Chi phí phát sinh</h2>
        <table>
            <tr>
                <th>Dịch vụ đồ ăn nhẹ:</th>
                <td>200,000 VND</td>
            </tr>
            <tr>
                <th>Bồi thường mất đồ:</th>
                <td>500,000 VND</td>
            </tr>
            <tr>
                <th>Chi phí giặt ủi:</th>
                <td>100,000 VND</td>
            </tr>
        </table>
    </section>

    <section class="price-info">
        <h2>Chi tiết thanh toán</h2>
        <table>
            <tr>
                <th>Giá mỗi đêm:</th>
                <td>2,000,000 VND</td>
            </tr>
            <tr>
                <th>Tổng số đêm:</th>
                <td>2</td>
            </tr>
            <tr>
                <th>Tổng tiền phòng:</th>
                <td>4,000,000 VND</td>
            </tr>
            <tr class="total">
                <th>Tổng chi phí phát sinh:</th>
                <td>800,000 VND</td>
            </tr>
            <tr class="total">
                <th>Tổng cộng:</th>
                <td>4,800,000 VND</td>
            </tr>
        </table>
    </section>

    <footer>
        <p>Khách sạn ABC rất mong được đón tiếp quý khách. Chúc bạn có kỳ nghỉ tuyệt vời!</p>
    </footer>
</div>
</body>
</html>
