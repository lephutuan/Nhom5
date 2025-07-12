// API Base URL - Dynamic configuration
const API_BASE_URL = CONFIG ? CONFIG.getApiBaseUrl() : './backend/api';

// API Helper Class
class API {
    static async request(endpoint, options = {}) {
        const url = `${API_BASE_URL}${endpoint}`;
        
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
            },
        };

        const config = { ...defaultOptions, ...options };

        try {
            const response = await fetch(url, config);
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'Có lỗi xảy ra');
            }
            
            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }

    // Thuốc APIs
    static async getThuoc() {
        return await this.request('/thuoc/read.php');
    }

    static async createThuoc(thuocData) {
        return await this.request('/thuoc/create.php', {
            method: 'POST',
            body: JSON.stringify(thuocData)
        });
    }

    static async updateThuoc(ma_thuoc, thuocData) {
        return await this.request(`/thuoc/update.php?id=${ma_thuoc}`, {
            method: 'PUT',
            body: JSON.stringify(thuocData)
        });
    }

    static async deleteThuoc(ma_thuoc) {
        return await this.request(`/thuoc/delete.php?id=${ma_thuoc}`, {
            method: 'DELETE'
        });
    }

    // Nhân viên APIs
    static async getNhanVien() {
        return await this.request('/nhanvien/read.php');
    }

    static async createNhanVien(nhanvienData) {
        return await this.request('/nhanvien/create.php', {
            method: 'POST',
            body: JSON.stringify(nhanvienData)
        });
    }

    static async updateNhanVien(ma_nv, nhanvienData) {
        return await this.request(`/nhanvien/update.php?id=${ma_nv}`, {
            method: 'PUT',
            body: JSON.stringify(nhanvienData)
        });
    }

    static async deleteNhanVien(ma_nv) {
        return await this.request(`/nhanvien/delete.php?id=${ma_nv}`, {
            method: 'DELETE'
        });
    }

    // Khách hàng APIs
    static async getKhachHang() {
        return await this.request('/khachhang/read.php');
    }

    static async createKhachHang(khachhangData) {
        return await this.request('/khachhang/create.php', {
            method: 'POST',
            body: JSON.stringify(khachhangData)
        });
    }

    static async updateKhachHang(ma_kh, khachhangData) {
        return await this.request(`/khachhang/update.php?id=${ma_kh}`, {
            method: 'PUT',
            body: JSON.stringify(khachhangData)
        });
    }

    static async deleteKhachHang(ma_kh) {
        return await this.request(`/khachhang/delete.php?id=${ma_kh}`, {
            method: 'DELETE'
        });
    }

    // Đơn thuốc APIs
    static async getDonThuoc() {
        return await this.request('/donthuoc/read.php');
    }

    static async createDonThuoc(donthuocData) {
        return await this.request('/donthuoc/create.php', {
            method: 'POST',
            body: JSON.stringify(donthuocData)
        });
    }

    static async updateDonThuoc(ma_don, donthuocData) {
        return await this.request(`/donthuoc/update.php?id=${ma_don}`, {
            method: 'PUT',
            body: JSON.stringify(donthuocData)
        });
    }

    static async deleteDonThuoc(ma_don) {
        return await this.request(`/donthuoc/delete.php?id=${ma_don}`, {
            method: 'DELETE'
        });
    }

    // Chi tiết đơn thuốc APIs
    static async getChiTietDonThuoc(ma_don) {
        return await this.request(`/chi-tiet-don-thuoc/read.php?ma_don=${ma_don}`);
    }

    static async createChiTietDonThuoc(chiTietData) {
        return await this.request('/chi-tiet-don-thuoc/create.php', {
            method: 'POST',
            body: JSON.stringify(chiTietData)
        });
    }
}

// UI Helper Functions
class UI {
    static showLoading() {
        // Implement loading indicator
        const loader = document.createElement('div');
        loader.id = 'loader';
        loader.innerHTML = '<div class="spinner"></div>';
        loader.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        `;
        document.body.appendChild(loader);
    }

    static hideLoading() {
        const loader = document.getElementById('loader');
        if (loader) {
            loader.remove();
        }
    }

    static showMessage(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.textContent = message;
        alertDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px;
            border-radius: 5px;
            z-index: 10000;
            max-width: 300px;
        `;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.classList.add('alert-hide');
            setTimeout(() => {
                alertDiv.remove();
            }, 800); // Thời gian trùng với transition trong CSS
        }, 3000);
    }

    static formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
    }

    static formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('vi-VN');
    }

    static confirmDelete(message = 'Bạn có chắc chắn muốn xóa?') {
        return confirm(message);
    }
}

// Event Handlers
document.addEventListener('DOMContentLoaded', function() {
    // Load data when page loads
    loadPageData();
});

async function loadPageData() {
    try {
        UI.showLoading();
        
        // Load data based on current page
        const currentPage = window.location.pathname.split('/').pop();
        
        switch(currentPage) {
            case 'home.html':
                await loadDashboardData();
                break;
            case 'products.html':
                await loadThuocData();
                break;
            case 'employees.html':
                await loadNhanVienData();
                break;
            case 'checkout.html':
                await loadCheckoutData();
                break;
            case 'reports.html':
                await loadReportsData();
                break;
            case 'warning.html':
                await loadWarningData();
                break;
        }
    } catch (error) {
        UI.showMessage('Lỗi khi tải dữ liệu: ' + error.message, 'error');
    } finally {
        UI.hideLoading();
    }
}

async function loadThuocData() {
    try {
        const data = await API.getThuoc();
        displayThuocTable(data.records);
    } catch (error) {
        UI.showMessage('Lỗi khi tải danh sách thuốc', 'error');
    }
}

async function loadNhanVienData() {
    try {
        const data = await API.getNhanVien();
        displayNhanVienTable(data.records);
    } catch (error) {
        UI.showMessage('Lỗi khi tải danh sách nhân viên', 'error');
    }
}

async function loadCheckoutData() {
    try {
        const [thuocData, khachhangData, nhanvienData] = await Promise.all([
            API.getThuoc(),
            API.getKhachHang(),
            API.getNhanVien()
        ]);
        
        populateSelectOptions('thuoc-select', thuocData.records, 'ma_thuoc', 'ten_thuoc');
        populateSelectOptions('khachhang-select', khachhangData.records, 'ma_kh', 'ten_kh');
        populateSelectOptions('nhanvien-select', nhanvienData.records, 'ma_nv', 'ten_nv');
    } catch (error) {
        UI.showMessage('Lỗi khi tải dữ liệu checkout', 'error');
    }
}

function displayThuocTable(thuocList) {
    const tableBody = document.querySelector('#thuoc-table tbody');
    if (!tableBody) return;

    tableBody.innerHTML = '';
    
    thuocList.forEach(thuoc => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${thuoc.ma_thuoc}</td>
            <td>${thuoc.ten_thuoc}</td>
            <td>${thuoc.don_vi}</td>
            <td>${UI.formatCurrency(thuoc.gia_nhap)}</td>
            <td>${UI.formatCurrency(thuoc.gia_ban)}</td>
            <td>${UI.formatDate(thuoc.ngay_them)}</td>
            <td>${thuoc.sl_toi_thieu}</td>
            <td>
                <button onclick="editThuoc(${thuoc.ma_thuoc})" class="btn btn-sm btn-primary">Sửa</button>
                <button onclick="deleteThuoc(${thuoc.ma_thuoc})" class="btn btn-sm btn-danger">Xóa</button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

function displayNhanVienTable(nhanvienList) {
    const tableBody = document.querySelector('#nhanvien-table tbody');
    if (!tableBody) return;

    tableBody.innerHTML = '';
    
    nhanvienList.forEach(nhanvien => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${nhanvien.ma_nv}</td>
            <td>${nhanvien.ten_nv}</td>
            <td>${nhanvien.chuc_vu}</td>
            <td>${nhanvien.sdt}</td>
            <td>${nhanvien.dia_chi}</td>
            <td>${nhanvien.trang_thai}</td>
            <td>
                <button onclick="editNhanVien(${nhanvien.ma_nv})" class="btn btn-sm btn-primary">Sửa</button>
                <button onclick="deleteNhanVien(${nhanvien.ma_nv})" class="btn btn-sm btn-danger">Xóa</button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

function populateSelectOptions(selectId, data, valueField, textField) {
    const select = document.getElementById(selectId);
    if (!select) return;

    select.innerHTML = '<option value="">Chọn...</option>';
    
    data.forEach(item => {
        const option = document.createElement('option');
        option.value = item[valueField];
        option.textContent = item[textField];
        select.appendChild(option);
    });
}

// CRUD Functions
async function createThuoc() {
    const formData = {
        ten_thuoc: document.getElementById('ten_thuoc').value,
        don_vi: document.getElementById('don_vi').value,
        gia_nhap: parseFloat(document.getElementById('gia_nhap').value),
        gia_ban: parseFloat(document.getElementById('gia_ban').value),
        sl_toi_thieu: parseInt(document.getElementById('sl_toi_thieu').value) || 10
    };

    try {
        await API.createThuoc(formData);
        UI.showMessage('Thêm thuốc thành công', 'success');
        loadThuocData();
    } catch (error) {
        UI.showMessage('Lỗi khi thêm thuốc: ' + error.message, 'error');
    }
}

async function deleteThuoc(ma_thuoc) {
    if (!UI.confirmDelete()) return;

    try {
        await API.deleteThuoc(ma_thuoc);
        UI.showMessage('Xóa thuốc thành công', 'success');
        loadThuocData();
    } catch (error) {
        UI.showMessage('Lỗi khi xóa thuốc: ' + error.message, 'error');
    }
}

// CRUD Functions for NhanVien
async function createNhanVien() {
    const formData = {
        ten_nv: document.getElementById('ten_nv').value,
        chuc_vu: document.getElementById('chuc_vu').value,
        sdt: document.getElementById('sdt').value,
        dia_chi: document.getElementById('dia_chi').value,
        trang_thai: document.getElementById('trang_thai').value
    };

    try {
        await API.createNhanVien(formData);
        UI.showMessage('Thêm nhân viên thành công', 'success');
        loadNhanVienData();
    } catch (error) {
        UI.showMessage('Lỗi khi thêm nhân viên: ' + error.message, 'error');
    }
}

async function deleteNhanVien(ma_nv) {
    if (!UI.confirmDelete()) return;

    try {
        await API.deleteNhanVien(ma_nv);
        UI.showMessage('Xóa nhân viên thành công', 'success');
        loadNhanVienData();
    } catch (error) {
        UI.showMessage('Lỗi khi xóa nhân viên: ' + error.message, 'error');
    }
}

// Export for use in HTML
window.API = API;
window.UI = UI;
window.createThuoc = createThuoc;
window.deleteThuoc = deleteThuoc;
window.createNhanVien = createNhanVien;
window.deleteNhanVien = deleteNhanVien; 