<div class="modal fade" id="addFiscalYearModal" tabindex="-1" role="dialog" aria-labelledby="addFiscalYearModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="addFiscalYearForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addFiscalYearModalLabel">Add Fiscal Year</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="addName">Name</label>
                        <input type="text" name="name" id="addName" class="form-control" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="addStartDateNp">Start Date (NP)</label>
                            <input type="text" name="start_date_np" id="addStartDateNp" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="addEndDateNp">End Date (NP)</label>
                            <input type="text" name="end_date_np" id="addEndDateNp" class="form-control" required>
                        </div>
                    </div>
                    <input type="hidden" name="start_date" id="addStartDate" class="form-control" required>
                    <input type="hidden" name="end_date" id="addEndDate" class="form-control" required>
                    <div class="form-group form-check">
                        <input type="checkbox" name="status" id="addStatus" value="1" class="form-check-input">
                        <label class="form-check-label" for="addStatus">Set as active fiscal year</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editFiscalYearModal" tabindex="-1" role="dialog" aria-labelledby="editFiscalYearModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editFiscalYearForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editFiscalYearId">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFiscalYearModalLabel">Edit Fiscal Year</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editName">Name</label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="editStartDateNp">Start Date (NP)</label>
                            <input type="text" name="start_date_np" id="editStartDateNp" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="editEndDateNp">End Date (NP)</label>
                            <input type="text" name="end_date_np" id="editEndDateNp" class="form-control" required>
                        </div>
                    </div>
                    <input type="hidden" name="start_date" id="editStartDate" class="form-control" required>
                    <input type="hidden" name="end_date" id="editEndDate" class="form-control" required>
                    <div class="form-group form-check">
                        <input type="checkbox" name="status" id="editStatus" value="1" class="form-check-input">
                        <label class="form-check-label" for="editStatus">Set as active fiscal year</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="viewFiscalYearModal" tabindex="-1" role="dialog" aria-labelledby="viewFiscalYearModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewFiscalYearModalLabel">Fiscal Year Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <dl class="row">
                    <dt class="col-sm-4">Name</dt>
                    <dd class="col-sm-8" id="viewName"></dd>
                    <dt class="col-sm-4">Start (NP)</dt>
                    <dd class="col-sm-8" id="viewStartDateNp"></dd>
                    <dt class="col-sm-4">End (NP)</dt>
                    <dd class="col-sm-8" id="viewEndDateNp"></dd>
                    <dt class="col-sm-4">Start (AD)</dt>
                    <dd class="col-sm-8" id="viewStartDate"></dd>
                    <dt class="col-sm-4">End (AD)</dt>
                    <dd class="col-sm-8" id="viewEndDate"></dd>
                    <dt class="col-sm-4">Status</dt>
                    <dd class="col-sm-8" id="viewStatus"></dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteFiscalYearModal" tabindex="-1" role="dialog" aria-labelledby="deleteFiscalYearModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteFiscalYearModalLabel">Delete Fiscal Year</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this fiscal year?
                <input type="hidden" id="deleteFiscalYearId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteFiscalYear">Delete</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="activateFiscalYearModal" tabindex="-1" role="dialog" aria-labelledby="activateFiscalYearModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="activateFiscalYearModalLabel">Activate Fiscal Year</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to activate this fiscal year? This will deactivate the current active year.
                <input type="hidden" id="activateFiscalYearId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmActivateFiscalYear">Activate</button>
            </div>
        </div>
    </div>
</div>
