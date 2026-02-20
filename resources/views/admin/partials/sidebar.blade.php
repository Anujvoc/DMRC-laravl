<!-- Sidebar -->
<style>
.app-menubar {
    width: 260px;
    height: 100vh;
    background: #2f4154;
    position: fixed;
    left: 0;
    top: 0;
    overflow-y: auto;
    font-family: 'Segoe UI', sans-serif;
}

/* Logo area */
.sidebar-header {
    padding: 20px;
    text-align: center;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}

.sidebar-header img {
    width: 150px;
}

/* Menu */
.menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.menu li {
    width: 100%;
}

/* Main Links */
.menu > li > a {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    color: #dce3ea;
    text-decoration: none;
    font-size: 15px;
    transition: 0.3s;
}

.menu > li > a i {
    margin-right: 12px;
}

.menu > li > a span {
    flex: 1;
}

/* Hover */
.menu > li > a:hover {
    background: #3d5368;
    color: #fff;
}

/* Arrow */
.arrow {
    border: solid #dce3ea;
    border-width: 0 2px 2px 0;
    display: inline-block;
    padding: 4px;
    transform: rotate(45deg);
    transition: 0.3s;
}

/* Submenu */
.submenu {
    list-style: none;
    padding: 0;
    display: none;
    background: #263746;
}

.submenu li a {
    display: block;
    padding: 10px 50px;
    font-size: 14px;
    color: #c8d3dc;
    text-decoration: none;
    transition: 0.3s;
}

.submenu li a:hover {
    background: #32495f;
    color: #fff;
}

/* Active */
.has-sub.active > .submenu {
    display: block;
}

.has-sub.active > a .arrow {
    transform: rotate(-135deg);
}
</style>
<aside class="app-menubar">
    @include('admin.partials.header')
  

    <ul class="menu">

        <li>
            <a href="#">
               <i class="fi fi-rr-home"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>Master Management</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('admin.master.companies') }}">Companies</a></li>
                <li><a href="{{ route('admin.master.designations') }}">Designations</a></li>
                <li><a href="{{ route('admin.master.training_types') }}">Training Types</a></li>
                <li><a href="{{ route('admin.master.venues') }}">Venues</a></li>
                 <li><a href="{{ route('admin.master.holidays') }}">Holidays</a></li>
                 <li><a href="{{ route('admin.master.instructors.index') }}">Instructors</a></li>
                 <li><a href="{{ route('admin.master.subjects.index') }}">Subjects</a></li>
                 <li><a href="{{ route('admin.master.certifications.index') }}">Certifications</a></li>
                 <li><a href="{{ route('admin.training_program.index') }}">Training Program</a></li>
                 <li><a href="{{ route('admin.categories.index') }}">Categories</a></li>
            </ul>
        </li>

        <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>Timetable management</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{url('admin/timetable-management/calender/batch')}}">Manage Timetable</a></li>
                <li><a href="#">Add Timetable Entry</a></li>
                <li><a href="#">Teacher Replacement</a></li>
                <li><a href="#">Daily Report</a></li>
            </ul>
        </li>

        <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>Training Batch Management</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('admin.training_batch.index') }}">Manage Batches</a></li>
                <li><a href="{{ route('admin.training_batch.create') }}">Add Batch</a></li>
                <li><a href="#">Analytics Dashboard</a></li>
                <li><a href="#">Compare Batches</a></li>
            </ul>
        </li>

            <li class="has-sub">
            <a href="javascript:void(0)">
              
                <span>Modules</span>
                <i class="arrow"></i>
            </a>
            <ul class="submenu">
               <li><a href="{{ route('admin.module.topics') }}">Topics</a></li>
                <li><a href="{{ route('admin.module.subtopics') }}">Sub Topics</a></li>
                <li><a href="{{ route('admin.module.master') }}">Module Master</a></li>
                <li><a href="{{ route('admin.module.master.store') }}">Add Module</a></li>
                <li><a href="{{ route('admin.module.master') }}">All Modules</a></li>
                <li><a href="#">Manage Categories</a></li>
            </ul>
        </li>



      
      
     

    </ul>
</aside>
 
<script>
document.addEventListener("DOMContentLoaded", function () {

    const menuItems = document.querySelectorAll(".has-sub > a");

    menuItems.forEach(item => {
        item.addEventListener("click", function (e) {
            e.preventDefault();

            const parent = this.parentElement;
            const submenu = parent.querySelector(".submenu");

            // Close other open menus
            document.querySelectorAll(".has-sub").forEach(menu => {
                if (menu !== parent) {
                    menu.classList.remove("active");
                    const otherSub = menu.querySelector(".submenu");
                    if (otherSub) {
                        otherSub.style.maxHeight = null;
                    }
                }
            });

            // Toggle current menu
            parent.classList.toggle("active");

            if (submenu.style.maxHeight) {
                submenu.style.maxHeight = null;
            } else {
                submenu.style.maxHeight = submenu.scrollHeight + "px";
            }
        });
    });

});
</script>


