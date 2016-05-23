<div class="panel panel-bordered">
    <div class="panel-heading">
        <h3>Routes</h3>

        <div class="panel-actions">
        </div>
    </div>
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
            <span class="sr-only">Close</span>
        </button>
        Lorem ipsum dolor sit amet, <a class="alert-link" href="javascript:void(0)">consectetur adipisicing elit</a>.
    </div>
    <div class="panel-body">

        <h4>Body Heading</h4>

        <p>
            <label>User</label>

            <a href="#" class="editable" id="username" data-type="text" data-title="Enter username">superuser</a>
        </p>

        <p>
            <label>Description</label>
            <a href="#" class="editable" id="description" data-type="textarea" data-title="Enter description">description</a>
        </p>

        <p>

        <div class="form-group">
            <label>Select a metropolis</label>
            <select class="form-control" multiple="multiple">
                <option value="AC">Achern</option>
                <option value="SW">Sasbachwalden</option>
                <option value="SS">Schifferstadt</option>
                <option value="NY">New York</option>
            </select>
        </div>
        </p>


        <input type="submit" class="btn btn-primary"/>
        <input type="submit" class="btn btn-success"/>
        <input type="submit" class="btn btn-danger"/>
        <input type="submit" class="btn btn-warning"/>
        <input type="submit" class="btn btn-info"/>
        <input type="submit" class="btn btn-default"/>
        <input type="submit" class="btn"/>

        <br/>
        <br/>


        <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control"/>
        </div>


        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group has-error">
                    <label>Alter</label>
                    <input type="text" class="form-control"/>

                    <div class="help-block">
                        Nobody can be that old
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group has-success">
                    <label>Beschreibung</label>
                    <textarea type="text" class="form-control"></textarea>
                </div>
            </div>
        </div>

    </div>
</div>