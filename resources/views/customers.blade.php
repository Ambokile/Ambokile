<div class="row" style="padding-left: 5%; padding-right: 5%;width: 90%">
    <div class="col-sm-9">
        <h2>Customers</h2>
    </div>
    <div class="col-sm-3">
        <button class="btn btn-primary">Import from VSC</button>
        <button class="btn btn-primary" onclick="LoadContent('addCustomers')">Add Customer</button>
    </div>
</div>
<div class="row">
    <div class="col-sm-12" style="padding-left: 5%; padding-right: 5%">
        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col" onclick="sortTable(0)">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <h5>Prisca</h5>
                    <h6><small>Prisca Shio</small></h6>
                </td>
                <td>Otto</td>
                <td>@mdo</td>
                <td>
                    <a  href="#">
                        <i class="fa fa-fw fa-trash"></i>
                        <span class="d-lg-none">Alerts</span>
                    </a>
                    <a  href="#">
                        <i class="fa fa-fw fa-pencil"></i>
                        <span class="d-lg-none">Edit</span>
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    <h5>Prisca</h5>
                    <h6><small>Prisca Shio</small></h6>
                </td>
                <td>Thornton</td>
                <td>@fat</td>
                <td>
                    <a  href="#">
                        <i class="fa fa-fw fa-trash"></i>
                        <span class="d-lg-none">Alerts</span>
                    </a>
                    <a  href="#">
                        <i class="fa fa-fw fa-pencil"></i>
                        <span class="d-lg-none">Edit</span>
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    <h5>Prisca</h5>
                    <h6><small>Prisca Shio</small></h6>
                </td>
                <td>the Bird</td>
                <td>@twitter</td>
                <td>
                    <a  href="#">
                        <i class="fa fa-fw fa-trash"></i>
                        <span class="d-lg-none">Alerts</span>
                    </a>
                    <a  href="#">
                        <i class="fa fa-fw fa-pencil"></i>
                        <span class="d-lg-none">Edit</span>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>