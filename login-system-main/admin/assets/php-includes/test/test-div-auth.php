
<style>
    .form-check-input[disabled] {
        opacity: 1; /* Set the opacity to 1 to make the checkbox fully visible */
        cursor: not-allowed; /* Change the cursor to indicate that the checkbox is not selectable */
    }

    .form-check-input[disabled] + .form-check-label {
        color: inherit; /* Inherit the color from the parent element */
        opacity: 1; /* Set the opacity to 1 to make the text fully visible */
        /* cursor: not-allowed; */
         /* Change the cursor to indicate that the checkbox is not selectable */
    }
</style>

<div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center" style="background: var(--bs-success-text-emphasis);">
        <h6 class="text-primary fw-bold m-0"><span style="color: rgb(244, 248, 244);">EMISSION TEST STATUS</span></h6>
    </div>
                                <div class="card-body">
                                
    <div class="mb-4 d-flex align-items-center">
        <span class="small fw-bold me-2">Auth.Code</span>
        <input class="text-black" id="authCode" value="<?php echo $AuthValue ?>" readonly>
    </div>


                                  
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                <th>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="tested" value="<?php echo $testedValue; ?>" <?php echo ($testedValue == 1) ? 'checked' : ''; ?> disabled>
        <label class="form-check-label" for="tested">
            <span style="font-weight: normal !important;">Tested</span>
        </label>
    </div>
</th>

                                                    <th>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="uploadedImage" value="<?php echo $UploadedImageValue; ?>" <?php echo ($UploadedImageValue == 1) ? 'checked' : ''; ?> disabled>
                                                            <label class="form-check-label" for="formCheck-6"><span style="font-weight: normal !important;">Uploaded Image</span></label></div>
                                                    </th>
                                                  
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="uploaded" value="<?php echo $UploadedValue; ?>" <?php echo ($UploadedValue == 1) ? 'checked' : ''; ?> disabled>
                                                        <label class="form-check-label" for="formCheck-5">Uploaded</label></div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="motorcyle" disabled>
                                                            <label class="form-check-label" for="formCheck-8">Motorcycle</label></div>
                                                    </td>
                                                    
                                                </tr>
                                                <tr>
                                    <td>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="valid" value="<?php echo $validValue; ?>" <?php echo ($validValue == 1) ? 'checked' : ''; ?>
        <?php echo ($UploadedImageValue) ? '' : 'disabled'; ?> >
        <label class="form-check-label" for="formCheck-7">Valid</label>
    </div>
</td>

                                                    
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="rebuilt" disabled>
                                                            <label class="form-check-label" for="formCheck-11">Rebuilt</label></div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>  


                            