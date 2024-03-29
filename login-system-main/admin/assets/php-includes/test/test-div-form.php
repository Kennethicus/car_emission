                    
                  <div class="col-12 col-md-8 col-lg-9 mx-auto">
    <div class="card shadow mb-4" >
        <div class="card-header d-flex justify-content-between align-items-center" style="background: var(--bs-success-text-emphasis);">
            <h6 class="text-primary fw-bold m-0"><span style="color: rgb(244, 248, 244);">MOTOR VEHICLE INFORMATION</span></h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <!-- Add your table headers here if needed -->
                                            </thead>
                                            <tbody>
                                            <tr>
                                            <td class="text-end" style="width: 200px;">Ticketing ID<input type="text" style="margin-left: 5px;" readonly value="<?php echo $details['ticketing_id']; ?>" disabled></td>
                                                    <td class="text-end" style="width: 200px;">Organization<input type="text" style="margin-left: 8px;" readonly value="<?php echo $details['organization']; ?>" disabled></td>
                                                </tr>
                                               <tr>
                                               <td class="text-end" style="width: 200px;">Plate No.<input type="text" style="margin-left: 5px;" readonly value="<?php echo $details['plate_number']; ?>" disabled></td>
                                                    <td class="text-end">Vehicle Owner<input type="text" style="margin-left: 5px;" readonly value="<?php echo $details['customer_first_name'] . ' ' . $details['customer_last_name']; ?>" disabled></td>
                                                </tr> 
                                            <tr>
                                            <td class="text-end">Year Model<input id="yearModeValue" type="text" style="margin-left: 5px;" readonly value="<?php echo $details['year_model']; ?>" disabled></td>
                                                    <td class="text-end" style="width: 200px;">Address<input type="text" style="margin-left: 8px;" readonly value="<?php echo $details['address']; ?>" disabled></td>
                                                </tr>
                                                <tr>
                                                <td class="text-end" style="vertical-align: middle;">Vehicle CR No.<input type="text" style="margin-left: 8px;" readonly value="<?php echo $details['vehicle_cr_no']; ?>" disabled></td>


                                                    <td class="text-end">Engine<input type="text" style="margin-left: 8px;" readonly value="<?php echo $details['engine']; ?>" disabled></td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: right;">Vehicle OR No.<input type="text" style="margin-left: 5px;" readonly value="<?php echo $details['vehicle_or_no']; ?>" disabled></td>
                                                    <td style="text-align: right;">Chassis<input type="text" style="margin-left: 5px;" readonly value="<?php echo $details['chassis']; ?>" disabled></td>
                                                </tr>
                                                <tr>
                                                <td class="text-end">First Registration Date<input type="text" style="margin-left: 5px;" readonly value="<?php echo $details['first_reg_date']; ?>" disabled></td>
                                                    <td class="text-end">Make<input type="text" style="margin-left: 8px;" readonly value="<?php echo $details['make']; ?>" disabled></td>
                                            </tr>
                                                <tr>
                                                <td class="text-end">MV File No.<input type="text" style="margin-left: 5px;" readonly value="<?php echo $details['mv_file_no']; ?>" disabled></td>                           
                                                    <td class="text-end">Series<input type="text" style="margin-left: 8px;" readonly value="<?php echo $details['series']; ?>" disabled></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">Fuel Type<input id="fuelTypeValue" type="text" style="margin-left: 5px;" readonly value="<?php echo $details['fuel_type']; ?>" disabled></td>
                                                        <td class="text-end">Color<input type="text" style="margin-left: 8px;" readonly value="<?php echo $details['color']; ?>" disabled></td>
                                                </tr>
                                                <tr>
                                                <td class="text-end">Classification<input type="text" style="margin-left: 5px;" readonly value="<?php echo $details['classification']; ?>" disabled></td>
                                                    <td class="text-end">Gross Weight<input type="text" style="margin-left: 8px;" readonly value="<?php echo $details['gross_weight']; ?>" disabled></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">MV Type<input id="mvTypeValue" type="text" style="margin-left: 5px;" readonly value="<?php echo $details['mv_type']; ?>" disabled></td>
                                                    <td class="text-end">Net Capacity<input type="text" style="margin-left: 8px;" readonly value="<?php echo $details['net_capacity']; ?>" disabled></td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td class="text-end">Region<input type="text" style="margin-left: 5px;" readonly value="<?php echo $details['region']; ?>" disabled></td>
                                                    
<!-- Add this inside the appropriate table row -->
<td class="text-end " >
Vehicle Picture  <button type="button" class="btn btn-primary "   data-bs-toggle="modal" data-bs-target="#carPictureModal" data-car-picture="../<?php echo $details['car_picture']; ?>">
    Display
    </button>
</td>

<!-- Modal for Car Picture -->
<div class="modal fade" id="carPictureModal" tabindex="-1" aria-labelledby="carPictureModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="carPictureModalLabel">Car Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="carPicture" class="img-fluid" alt="Car Picture">
            </div>
        </div>
    </div>
</div>
                                                </tr>                                                                                          
                                            
                                                <tr>
                                                    <td class="text-end">CEC Number<input id="cecValue" type="text" style="margin-left: 5px;" readonly value="<?php echo $details['cec_number']; ?>" disabled></td>
                                                    <td class="text-end " >
OR Picture  <button type="button" class="btn btn-primary "   data-bs-toggle="modal" data-bs-target="#carPictureModal" data-car-picture="../<?php echo $details['car_picture']; ?>">
    Display
    </button>
</td>
                                                </tr>               
                                                <tr>
                                                <td></td>    
                                                <td class="text-end " >
CR Picture  <button type="button" class="btn btn-primary "   data-bs-toggle="modal" data-bs-target="#carPictureModal" data-car-picture="../<?php echo $details['car_picture']; ?>">
    Display
    </button>
</td></tr>              
                                                <!-- Add more rows based on your structure -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>