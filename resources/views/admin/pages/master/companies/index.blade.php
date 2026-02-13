@extends('admin.partials.app')

@section('title', 'Companies')

@section('content')
<style>
    th{
          font-size: 14px;
    font-weight: 800;
    color: gray;
    }

      
</style>

<div class="app-wrapper">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Companies Management</h2>

    <a href="{{ route('admin.master.companies.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> Add New Company
    </a>
</div>

    
 

    <!-- Statistics Cards -->


    <!-- Companies Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <!-- Table View -->
            <div id="tableView">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="companiesTable">
                        <thead>
                            <tr>
                               
                                <th>Company</th>
                                <th>Code</th>
                                <th>Address</th>
                                <th>Phone</th>
                               
                                <th>Email</th>
                                <th>Website</th>
                                <th>Contact Person</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($companies as $company)
                                <tr>
                                 
                                    <td>
                                        <div class="d-flex align-items-center">
                                          
                                            <div>
                                                <div class="fw-semibold">{{ $company->company_name ?? 'Unknown Company' }}</div>
                                                <small class="text-muted">{{ $company->company_code ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $company->company_code ?? 'N/A' }}</td>
                                    <td>{{ $company->address ?? 'N/A' }}</td>
                                    <td>{{ $company->phone ?? 'N/A' }}</td>
                                   
                                    <td>{{ $company->email ?? 'N/A' }}</td>
                                    <td>{{ $company->website ?? 'N/A' }}</td>
                                    <td>{{ $company->contact_person ?? 'N/A' }}</td>
                                    <td>
                                        @if(($company->is_active ?? 1) == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-1 justify-content-end">
                                            <a href="{{ route('admin.master.companies.edit', $company->company_id) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger btn-icon" title="Delete" onclick="deleteCompany({{ $company->company_id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fi fi-sr-building fa-3x mb-3"></i>
                                            <h5>No companies found</h5>
                                            <p>Start by adding your first company.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Grid View -->
            <div id="gridView" style="display: none;">
                <div class="row g-3" id="companiesGrid">
                    <!-- Companies will be loaded here via JavaScript -->
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
               
                <nav>
                    <ul class="pagination mb-0" id="pagination">
                        <!-- Pagination will be generated here -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Add Company Modal -->

@endsection

@push('scripts')
<script>
// Delete company function
function deleteCompany(companyId) {
    if (confirm('Are you sure you want to delete this company? This action cannot be undone.')) {
        // Create form for DELETE method
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.master.companies.destroy", ":id") }}'.replace(':id', companyId);
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add DELETE method override
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        // Submit form
        document.body.appendChild(form);
        form.submit();
    }
}

// Sample data - replace with actual API calls
let companies = [
    {
        id: 1,
        name: "Tech Solutions Inc.",
        code: "TSI001",
        contactPerson: "John Smith",
        email: "john@techsolutions.com",
        phone: "+1-555-0123",
        industry: "technology",
        size: "medium",
        website: "https://techsolutions.com",
        address: "123 Tech Street, Silicon Valley, CA 94000",
        description: "Leading technology solutions provider",
        status: "active",
        logo: null,
        created_at: "2024-01-15",
        updated_at: "2024-01-20"
    },
    {
        id: 2,
        name: "Healthcare Plus",
        code: "HCP002",
        contactPerson: "Sarah Johnson",
        email: "sarah@healthcareplus.com",
        phone: "+1-555-0124",
        industry: "healthcare",
        size: "large",
        website: "https://healthcareplus.com",
        address: "456 Medical Ave, Boston, MA 02100",
        description: "Comprehensive healthcare services",
        status: "active",
        logo: null,
        created_at: "2024-01-10",
        updated_at: "2024-01-18"
    },
    {
        id: 3,
        name: "Finance Corp",
        code: "FCP003",
        contactPerson: "Michael Brown",
        email: "michael@financecorp.com",
        phone: "+1-555-0125",
        industry: "finance",
        size: "enterprise",
        website: "https://financecorp.com",
        address: "789 Wall Street, New York, NY 10000",
        description: "Financial services and consulting",
        status: "inactive",
        logo: null,
        created_at: "2024-01-05",
        updated_at: "2024-01-15"
    }
];

let currentPage = 1;
let itemsPerPage = 10;
let filteredCompanies = [...companies];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadCompanies();
    updateStatistics();
    setupEventListeners();
});

function setupEventListeners() {
    // Search
    document.getElementById('searchInput').addEventListener('input', filterCompanies);
    
    // Filters
    document.getElementById('statusFilter').addEventListener('change', filterCompanies);
    document.getElementById('industryFilter').addEventListener('change', filterCompanies);
    document.getElementById('sizeFilter').addEventListener('change', filterCompanies);
    
    // Select all checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('#companiesTableBody input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
}

function loadCompanies() {
    renderTableView();
    updatePagination();
}

function filterCompanies() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const industryFilter = document.getElementById('industryFilter').value;
    const sizeFilter = document.getElementById('sizeFilter').value;
    
    filteredCompanies = companies.filter(company => {
        const matchesSearch = company.name.toLowerCase().includes(searchTerm) ||
                             company.contactPerson.toLowerCase().includes(searchTerm) ||
                             company.email.toLowerCase().includes(searchTerm);
        const matchesStatus = !statusFilter || company.status === statusFilter;
        const matchesIndustry = !industryFilter || company.industry === industryFilter;
        const matchesSize = !sizeFilter || company.size === sizeFilter;
        
        return matchesSearch && matchesStatus && matchesIndustry && matchesSize;
    });
    
    currentPage = 1;
    loadCompanies();
}

function renderTableView() {
    const tbody = document.getElementById('companiesTableBody');
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageCompanies = filteredCompanies.slice(start, end);
    
    tbody.innerHTML = pageCompanies.map(company => `
        <tr>
            <td>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="${company.id}">
                    <label class="form-check-label"></label>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm bg-primary bg-opacity-10 rounded-circle text-primary me-2">
                        <i class="fi fi-sr-building"></i>
                    </div>
                    <div>
                        <div class="fw-semibold">${company.name}</div>
                        <small class="text-muted">${company.code}</small>
                    </div>
                </div>
            </td>
            <td>${company.contactPerson}</td>
            <td>${company.email}</td>
            <td>${company.phone}</td>
            <td><span class="badge bg-light text-dark">${getIndustryLabel(company.industry)}</span></td>
            <td><span class="badge bg-light text-dark">${getSizeLabel(company.size)}</span></td>
            <td>${getStatusBadge(company.status)}</td>
            <td>${formatDate(company.created_at)}</td>
            <td class="text-end">
                <div class="dropdown">
                    <button class="btn btn-sm btn-action-gray btn-icon waves-effect waves-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fi fi-rr-menu-dots"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#" onclick="viewCompany(${company.id})"><i class="fi fi-rr-eye me-2"></i>View</a></li>
                        <li><a class="dropdown-item" href="#" onclick="editCompany(${company.id})"><i class="fi fi-rr-edit me-2"></i>Edit</a></li>
                        <li><a class="dropdown-item" href="#" onclick="deleteCompany(${company.id})"><i class="fi fi-rr-trash me-2"></i>Delete</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    `).join('');
}

function updatePagination() {
    const totalPages = Math.ceil(filteredCompanies.length / itemsPerPage);
    const pagination = document.getElementById('pagination');
    
    let paginationHTML = '';
    
    // Previous button
    paginationHTML += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage - 1})">Previous</a>
        </li>
    `;
    
    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
            paginationHTML += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                </li>
            `;
        } else if (i === currentPage - 3 || i === currentPage + 3) {
            paginationHTML += '<li class="page-item disabled"><a class="page-link">...</a></li>';
        }
    }
    
    // Next button
    paginationHTML += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage + 1})">Next</a>
        </li>
    `;
    
    pagination.innerHTML = paginationHTML;
    
    // Update showing info
    const start = (currentPage - 1) * itemsPerPage + 1;
    const end = Math.min(currentPage * itemsPerPage, filteredCompanies.length);
    document.getElementById('showingFrom').textContent = start;
    document.getElementById('showingTo').textContent = end;
    document.getElementById('totalRecords').textContent = filteredCompanies.length;
}

function changePage(page) {
    const totalPages = Math.ceil(filteredCompanies.length / itemsPerPage);
    if (page >= 1 && page <= totalPages) {
        currentPage = page;
        loadCompanies();
    }
}

function toggleTableView() {
    document.getElementById('tableView').style.display = 'block';
    document.getElementById('gridView').style.display = 'none';
    renderTableView();
}

function toggleGridView() {
    document.getElementById('tableView').style.display = 'none';
    document.getElementById('gridView').style.display = 'block';
    renderGridView();
}

function renderGridView() {
    const grid = document.getElementById('companiesGrid');
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageCompanies = filteredCompanies.slice(start, end);
    
    grid.innerHTML = pageCompanies.map(company => `
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-md bg-primary bg-opacity-10 rounded-circle text-primary me-3">
                            <i class="fi fi-sr-building"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-0">${company.name}</h6>
                            <small class="text-muted">${company.code}</small>
                        </div>
                        ${getStatusBadge(company.status)}
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Contact Person</small>
                        <div>${company.contactPerson}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Email</small>
                        <div>${company.email}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Industry</small>
                        <div><span class="badge bg-light text-dark">${getIndustryLabel(company.industry)}</span></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <small class="text-muted">${formatDate(company.created_at)}</small>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-action-gray btn-icon waves-effect waves-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fi fi-rr-menu-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" onclick="viewCompany(${company.id})"><i class="fi fi-rr-eye me-2"></i>View</a></li>
                                <li><a class="dropdown-item" href="#" onclick="editCompany(${company.id})"><i class="fi fi-rr-edit me-2"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#" onclick="deleteCompany(${company.id})"><i class="fi fi-rr-trash me-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function updateStatistics() {
    const total = companies.length;
    const active = companies.filter(c => c.status === 'active').length;
    const inactive = companies.filter(c => c.status === 'inactive').length;
    const thisMonth = companies.filter(c => {
        const createdDate = new Date(c.created_at);
        const now = new Date();
        return createdDate.getMonth() === now.getMonth() && createdDate.getFullYear() === now.getFullYear();
    }).length;
    
    document.getElementById('totalCompanies').textContent = total;
    document.getElementById('activeCompanies').textContent = active;
    document.getElementById('inactiveCompanies').textContent = inactive;
    document.getElementById('newCompaniesThisMonth').textContent = thisMonth;
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('industryFilter').value = '';
    document.getElementById('sizeFilter').value = '';
    filterCompanies();
}

function saveCompany() {
    // Collect form data
    const formData = {
        name: document.getElementById('companyName').value,
        code: document.getElementById('companyCode').value,
        contactPerson: document.getElementById('contactPerson').value,
        email: document.getElementById('contactEmail').value,
        phone: document.getElementById('contactPhone').value,
        industry: document.getElementById('industry').value,
        size: document.getElementById('companySize').value,
        website: document.getElementById('website').value,
        address: document.getElementById('address').value,
        description: document.getElementById('description').value,
        status: document.getElementById('status').value,
        created_at: new Date().toISOString().split('T')[0],
        updated_at: new Date().toISOString().split('T')[0]
    };
    
    // Add to companies array (replace with actual API call)
    const newCompany = {
        id: companies.length + 1,
        ...formData,
        logo: null
    };
    
    companies.push(newCompany);
    filteredCompanies = [...companies];
    
    // Close modal and reset form
    bootstrap.Modal.getInstance(document.getElementById('addCompanyModal')).hide();
    document.getElementById('addCompanyForm').reset();
    
    // Reload data
    loadCompanies();
    updateStatistics();
    
    // Show success message
    showAlert('Company added successfully!', 'success');
}

function viewCompany(id) {
    const company = companies.find(c => c.id === id);
    if (!company) return;
    
    // Simple alert for now - replace with modal
    alert(`Company Details:\n\nName: ${company.name}\nContact: ${company.contactPerson}\nEmail: ${company.email}\nPhone: ${company.phone}\nIndustry: ${company.industry}\nStatus: ${company.status}`);
}

function editCompany(id) {
    const company = companies.find(c => c.id === id);
    if (!company) return;
    
    // Simple prompt for now - replace with modal
    const newName = prompt('Edit company name:', company.name);
    if (newName && newName !== company.name) {
        company.name = newName;
        company.updated_at = new Date().toISOString().split('T')[0];
        
        showAlert('Company updated successfully!', 'success');
    }
}


function exportCompanies() {
    // Simple CSV export
    const csvContent = [
        ['Name', 'Code', 'Contact Person', 'Email', 'Phone', 'Industry', 'Size', 'Status', 'Created'],
        ...filteredCompanies.map(company => [
            company.name,
            company.code,
            company.contactPerson,
            company.email,
            company.phone,
            getIndustryLabel(company.industry),
            getSizeLabel(company.size),
            company.status,
            company.created_at
        ])
    ].map(row => row.join(',')).join('\n');
    
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'companies.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

// Utility functions
function getIndustryLabel(industry) {
    const labels = {
        'technology': 'Technology',
        'healthcare': 'Healthcare',
        'finance': 'Finance',
        'education': 'Education',
        'retail': 'Retail',
        'manufacturing': 'Manufacturing',
        'other': 'Other'
    };
    return labels[industry] || industry;
}

function getSizeLabel(size) {
    const labels = {
        'small': 'Small (1-50)',
        'medium': 'Medium (51-200)',
        'large': 'Large (201-1000)',
        'enterprise': 'Enterprise (1000+)'
    };
    return labels[size] || size;
}

function getStatusBadge(status) {
    const badges = {
        'active': '<span class="badge bg-success">Active</span>',
        'inactive': '<span class="badge bg-danger">Inactive</span>',
        'pending': '<span class="badge bg-warning">Pending</span>'
    };
    return badges[status] || status;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

function showAlert(message, type) {
    // Simple alert - replace with proper toast notification
    alert(message);
}

function deleteCompany(companyId) {
    if (confirm('Are you sure you want to delete this company? This action cannot be undone.')) {
        // Create form for DELETE method
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.master.companies.destroy", ":id") }}'.replace(':id', companyId);
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add DELETE method override
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        // Submit form
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush