let CarService = {
    init: function () {
        $("#addCarForm").validate({
            rules: {
                model: 'required',
                price: {
                    required: true,
                    number: true
                },
                mileage: {
                    required: true,
                    number: true
                }
            },
            messages: {
                model: 'Please select a model',
                price: {
                    required: 'Please enter the price',
                    number: 'Price must be a number'
                },
                mileage: {
                    required: 'Please enter the mileage',
                    number: 'Mileage must be a number'
                }
            },
            submitHandler: function (form) {
                let car = Object.fromEntries(new FormData(form).entries());
                CarService.addCar(car);
                form.reset();
            },
        });
       
        $("#editCarForm").validate({
            submitHandler: function (form) {
                let car = Object.fromEntries(new FormData(form).entries());
                CarService.editCar(car);
            },
        });

        CarService.getAllCars();
    },

    openAddModal: function() {
        RestClient.get('brands', function(brands) {
            $('#brand').empty().append('<option value="">Select Brand</option>');
            brands.forEach(brand => {
                $('#brand').append(`<option value="${brand.id}">${brand.brand_name}</option>`);
            });
        });
        
        $('#addCarModal').show();
    }, 

    loadModels: function(brandId) {
        if(brandId) {
            RestClient.get(`models?brand_id=${brandId}`, function(models) {
                $('#model').empty().append('<option value="">Select Model</option>');
                models.forEach(model => {
                    $('#model').append(`<option value="${model.id}">${model.model_name}</option>`);
                });
            });
        }
    },

    addCar: function (car) {
        $.blockUI({ message: '<h3>Processing...</h3>' });
        RestClient.post('cars', car, function(response){
            toastr.success("Car added successfully");
            $.unblockUI();
            CarService.getAllCars();
            CarService.closeModal();
        }, function(response){
            CarService.closeModal();
            toastr.error(response.message);
        });
    },

    getAllCars: function(){
        RestClient.get("cars", function(data){
            Utils.datatable('cars-table', [
                { data: 'id', title: 'ID' },
                { 
                    data: 'model', 
                    title: 'Model',
                render: function(data, type, row) {
                    
                    const modelName = row.model?.model_name || 'N/A';
                    const brandName = row.model?.brand?.brand_name || 'N/A';
                    return `${modelName} (${brandName})`;
                }
                },
                { data: 'price', title: 'Price' },
                { data: 'mileage', title: 'Mileage' },
                { data: 'color', title: 'Color' },
                { data: 'state', title: 'Condition' },
                {
                    title: 'Actions',
                    render: function (data, type, row, meta) {
                        const rowStr = encodeURIComponent(JSON.stringify(row)); 
                        return `<div class="d-flex justify-content-center gap-2 mt-3">
                            <button class="btn btn-primary" onclick="CarService.openEditModal('${row.id}')">Edit</button>
                            <button class="btn btn-danger" onclick="CarService.openConfirmationDialog(decodeURIComponent('${rowStr}'))">Delete</button>
                            <button class="btn btn-secondary" onclick="CarService.openViewMore('${row.id}')">Details</button>
                        </div>`;
                    }
                }
            ], data, 10);
        }, function (xhr, status, error) {
            console.error('Error fetching cars:', error);
        });
    },

    getCarById: function(id) {
        RestClient.get('cars/' + id, function (data) {
            localStorage.setItem('selected_car', JSON.stringify(data));
            $('input[name="id"]').val(data.id);
            $('input[name="price"]').val(data.price);
            $('input[name="mileage"]').val(data.mileage);
            $('input[name="color"]').val(data.color);
            $('select[name="state"]').val(data.state);

            // First fetch all brands
            RestClient.get('brands', function (brands) {
                $('#edit_brand').empty().append('<option value="">Select Brand</option>');
                brands.forEach(brand => {
                    $('#edit_brand').append(`<option value="${brand.id}">${brand.brand_name}</option>`);
                });
                const modelId = data.model.id;
                RestClient.get('models/' + data.model, function (modelData) {
                    const brandId = modelData.brand.id;
                    $('#edit_brand').val(brandId);
                    RestClient.get(`models?brand_id=${brandId}`, function (models) {
                        $('#edit_model').empty().append('<option value="">Select Model</option>');
                        models.forEach(model => {
                            const selected = model.id === modelData.id ? 'selected' : '';
                            $('#edit_model').append(`<option value="${model.id}" ${selected}>${model.model_name}</option>`);
                        });
                    });
                });
            });

            $.unblockUI();
        }, function (xhr, status, error) {
            console.error('Error fetching car details');
            $.unblockUI();
        });
    },
    

    openViewMore: function(id) {
        window.location.replace("#view_more");
        CarService.getCarById(id);
    },

    populateViewMore: function(){
        let selected_car = JSON.parse(localStorage.getItem('selected_car'));
        $("#car-model").text(`${selected_car.model.model_name} (${selected_car.model.brand.brand_name})`);
        $("#car-price").text(selected_car.price);
        $("#car-mileage").text(selected_car.mileage);
        $("#car-color").text(selected_car.color);
        $("#car-state").text(selected_car.state);
        $("#car-description").text(selected_car.description || 'No description available');
    },

    openEditModal: function(id) {
        $.blockUI({ message: '<h3>Processing...</h3>' });
        $('#editCarModal').show();
        CarService.getCarById(id);  
    }, 

    closeModal: function() {
        $('#editCarModal').hide();
        $("#deleteCarModal").modal("hide");
        $('#addCarModal').hide();
    },

    editCar: function(car){
        $.blockUI({ message: '<h3>Processing...</h3>' });
        RestClient.patch('cars/' + car.id, car, function (data) {
            $.unblockUI();
            toastr.success("Car updated successfully");
            CarService.closeModal();
            CarService.getAllCars();
        }, function (xhr, status, error) {
            console.error('Error updating car');
            $.unblockUI();
        });
    },

    openConfirmationDialog: function (car) {
        car = JSON.parse(car);
        $("#deleteCarModal").modal("show");
        $("#delete-car-body").html(
            `Are you sure you want to delete this car: ${car.model.model_name} (${car.model.brand.brand_name})?`
        );
        $("#delete_car_id").val(car.id);
    },

    deleteCar: function () {
        RestClient.delete('cars/' + $("#delete_car_id").val(), null, function(response){
            CarService.closeModal();
            toastr.success(response.message);
            CarService.getAllCars();
        }, function(response){
            CarService.closeModal();
            toastr.error(response.message);
        });
    }
};