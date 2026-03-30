import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const header = document.querySelector('[data-app-header]');

    if (!header) {
        return;
    }

    // Biến cờ lưu trạng thái để tối ưu hiệu suất (tránh can thiệp DOM liên tục)
    let isCompact = false;

    const syncCompactHeader = () => {
        const currentScroll = window.scrollY;

        // KHI CUỘN XUỐNG: Vượt qua mốc 120px mới bắt đầu thu nhỏ
        if (currentScroll > 120 && !isCompact) {
            header.classList.add('is-compact');
            isCompact = true;
        } 
        // KHI CUỘN LÊN: Phải về tận mốc 70px mới được phóng to trở lại
        // (Tạo ra một "vùng an toàn" chênh lệch 50px để chống giật)
        else if (currentScroll < 70 && isCompact) {
            header.classList.remove('is-compact');
            isCompact = false;
        }
    };

    // Chạy thử một lần khi vừa load trang
    syncCompactHeader();
    
    // Gắn sự kiện lắng nghe thao tác cuộn chuột
    window.addEventListener('scroll', syncCompactHeader, { passive: true });
});