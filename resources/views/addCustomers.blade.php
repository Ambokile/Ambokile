<div class="row" style="padding-left: 5%; padding-right: 5%;margin-top: 3%;">
    <div class="col-sm-9">
        <h3>Add Customers</h3>
    </div>
    <div class="col-sm-3">
        <button class="btn btn-primary" onclick="LoadContent('customers')">Back</button>
    </div>
</div>
<div class="row" style="width: 90%;margin-top: 5%;font-size: 14px;">
    <div class="col-sm-8 offset-md-2">
        <div class="form-group row">
            <label class="control-label col-sm-3" for="name">customer name:</label>
            <div class="col-sm-9">
                <input type="name" class="form-control" id="name" placeholder="Enter customer name" style="width: 90%;font-size: 14px" autocomplete="off">
                @if ($errors->has('name')) <p class="help-block" style="font-size: 14px;color: red">{{ $errors->first('name') }}</p> @endif
            </div>
        </div>
        <div class="form-group row">
            <label class="control-label col-sm-3" for="email">Email:</label>
            <div class="col-sm-9">
                <input type="email" class="form-control" id="email" placeholder="Enter email" style="width: 90%;font-size: 14px" autocomplete="off">
                @if ($errors->has('email')) <p class="help-block" style="font-size: 14px;color: red">{{ $errors->first('email') }}</p> @endif
            </div>
        </div>
        <div class="form-group row">
            <label class="control-label col-sm-3" for="email">First Name:</label>
            <div class="col-sm-9">
                <input type="name" class="form-control" id="fname" placeholder="Enter name" style="width: 90%;font-size: 14px" autocomplete="off">
                @if ($errors->has('first_name')) <p class="help-block" style="font-size: 14px;color: red">{{ $errors->first('first_name') }}</p> @endif
            </div>
        </div>

        <div class="form-group row">
            <label class="control-label col-sm-3" for="email">Last Name:</label>
            <div class="col-sm-9">
                <input type="name" class="form-control" id="lname" placeholder="Enter name" style="width: 90%;font-size: 14px" autocomplete="off">
                @if ($errors->has('last_name')) <p class="help-block" style="font-size: 14px;color: red">{{ $errors->first('last_name') }}</p> @endif
            </div>
        </div>

        <div class="form-group row">
            <label class="control-label col-sm-3" for="email">Phone:</label>
            <div class="col-sm-9">
                <input type="phone" class="form-control" id="phone" placeholder="Enter phone" style="width: 90%;font-size: 14px" autocomplete="off">
                @if ($errors->has('phone')) <p class="help-block" style="font-size: 14px;color: red">{{ $errors->first('phone') }}</p> @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="sel1" class="col-sm-3">currency:</label>
            <div class="col-sm-9">
                <select class="form-control js-example-basic-multiple" name="currency" id="currency" style="width: 45%;font-size: 14px">
                    <option value="Tshs">Tshs</option>
                    <option value="USD">USD</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="button" class="btn btn-info" onclick="insertVendor(1)">Submit</button>
            </div>
        </div>
    </div>
</div>
