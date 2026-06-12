
	  $(document).ready(function() {
		  $.ajaxSetup({
			  headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
		  });

          // Function to refresh reports table and dropdowns without a full page reload
          window.refreshReportsPageData = function(modalToCloseId = null, btnToReset = null, defaultBtnText = '') {
              if (modalToCloseId) {
                  $(modalToCloseId).modal('hide');
              }
              if (btnToReset) {
                  btnToReset.html(defaultBtnText).prop('disabled', false);
              }
              
              let currentUrl = window.location.href;
              $.ajax({
                  url: currentUrl,
                  type: 'GET',
                  cache: false, // PREVENT AGGRESSIVE BROWSER CACHING
                  success: function(html) {
                      let newDoc = new DOMParser().parseFromString(html, 'text/html');
                      
                      // 1. Update the main reports table
                      let newTableWrapper = $(newDoc).find('#report-table').closest('.table-responsive-modern').html();
                      if (newTableWrapper) {
                          let dtState = { page: 0, length: 10, search: '' };
                          if($.fn.DataTable.isDataTable('#report-table')) {
                              let dt = $('#report-table').DataTable();
                              dtState.page = dt.page();
                              dtState.length = dt.page.len();
                              dtState.search = dt.search();
                              dt.destroy();
                          }
                          
                          // Specifically target the wrapper of report-table to avoid affecting modal tables
                          $('#report-table').closest('.table-responsive-modern').html(newTableWrapper);
                          
                          // Re-initialize reports table with preserved state
                          var reportsTable = $('#report-table').DataTable({
                              dom: "<'row mb-3'<'col-sm-12 col-md-6'l>>" +
                                   "<'row'<'col-sm-12'tr>>" +
                                   "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                              pageLength: dtState.length,
                              search: { search: dtState.search },
                              lengthMenu: [5, 10, 25, 50, 100],
                              ordering: false,
                              language: {
                                  lengthMenu: "Show _MENU_ records",
                                  info: "Showing _START_ to _END_ of _TOTAL_ reports",
                                  infoEmpty: "Showing 0 to 0 of 0 reports",
                                  infoFiltered: "(filtered from _MAX_ total reports)",
                                  emptyTable: "No test reports generated yet.",
                                  paginate: {
                                      previous: "<i class='fa fa-angle-left'></i>",
                                      next: "<i class='fa fa-angle-right'></i>"
                                  }
                              }
                          });
                          
                          reportsTable.page(dtState.page).draw('page');

                          $("#report-search").off("keyup").on("keyup", function() {
                              reportsTable.search($(this).val()).draw();
                          });
                      }

                      // 2. Update the "View Details" modal tables so "all pop" stay refreshed
                      let newModalDetail = $(newDoc).find('#modal-view-detail .modal-body').html();
                      if (newModalDetail) {
                          $('#modal-view-detail .modal-body').html(newModalDetail);
                      }
                      
                      // 3. Dynamically update ALL dropdown <select> contents without destroying bindings
                      const selectNamesToUpdate = [
                          'patient_id', 'doctor_name', 'report_signature_id', 'category_id', 'unit',
                          'test_category[]', 'test_subcategory[]', 'test_name[]', 'observed_value[]', 
                          'test_unit[]', 'normal_value[]', 'test_flag[]', 'biological_reference[]'
                      ];
                      
                      selectNamesToUpdate.forEach(name => {
                          let newOptions = $(newDoc).find(`select[name="${name}"]`).first().html();
                          if (newOptions) {
                              $(`select[name="${name}"]`).each(function() {
                                  let currentVal = $(this).val();
                                  $(this).html(newOptions);
                                  $(this).val(currentVal); // Restore user selection if still exists
                              });
                          }
                      });
                  }
              });
          };

          // Fix overlapping modal backdrops z-index
          $(document).on('show.bs.modal', '.modal', function () {
              const zIndex = 1060 + (10 * $('.modal:visible').length);
              $(this).css('z-index', zIndex);
              setTimeout(function() {
                  $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
              }, 0);
          });
          $(document).on('hidden.bs.modal', '.modal', function () {
              if ($('.modal:visible').length > 0) {
                  // Restore modal-open class to body if another modal is still open
                  setTimeout(function() {
                      $('body').addClass('modal-open');
                  }, 0);
              }
          });

		  // Initialize DataTables for Reports
		  var reportsTable = $('#report-table').DataTable({
			  dom: "<'row mb-3'<'col-sm-12 col-md-6'l>>" +
				   "<'row'<'col-sm-12'tr>>" +
				   "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			  pageLength: 10,
			  lengthMenu: [5, 10, 25, 50, 100],
			  ordering: false,
			  language: {
				  lengthMenu: "Show _MENU_ records",
				  info: "Showing _START_ to _END_ of _TOTAL_ reports",
				  infoEmpty: "Showing 0 to 0 of 0 reports",
				  infoFiltered: "(filtered from _MAX_ total reports)",
				  emptyTable: "No test reports generated yet.",
				  paginate: {
					  previous: "<i class='fa fa-angle-left'></i>",
					  next: "<i class='fa fa-angle-right'></i>"
				  }
			  }
		  });
		  $("#report-search").on("keyup", function() {
			  reportsTable.search($(this).val()).draw();
		  });

          // Standard Tests Dictionary for Auto-Fill
          const standardTests = {
              "Blood Glucose (Fasting)": { category: "BIOCHEMISTRY", normal: "60 - 110 mg/dl" },
              "Total Cholesterol": { category: "LIPID PROFILE (FASTING)", normal: "130 - 220 mg/dl" },
              "Triglycerides": { category: "LIPID PROFILE (FASTING)", normal: "40 - 170 mg/dl" },
              "HDL Cholesterol": { category: "LIPID PROFILE (FASTING)", normal: "30 - 70 mg/dl" },
              "LDL Cholesterol": { category: "LIPID PROFILE (FASTING)", normal: "60 - 160 mg/dl" },
              "VLDL": { category: "LIPID PROFILE (FASTING)", normal: "8 - 32 mg/dl" },
              "Cholesterol / HDL Ratio": { category: "LIPID PROFILE (FASTING)", normal: "< 4" },
              "LDL / HDL Ratio": { category: "LIPID PROFILE (FASTING)", normal: "< 3.5" }
          };

          const REPORT_HEADER_IMAGE = "data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/report-header-awwal.png'))) }}";
          const REPORT_FOOTER_IMAGE = "data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/report-footer-awwal.png'))) }}";

          // Dynamic Rows Logic (Refined Alignment & Auto-fill)
          const trTemplate = `
            <div class="test-item-row card border-0 shadow-sm mb-3" style="background: linear-gradient(145deg, #ffffff, #f8fafc); border-radius: 12px; position: relative; overflow: hidden;">
                <div style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: #6366f1;"></div>
                <div class="card-body p-3">
                    <button type="button" class="btn btn-sm btn-danger position-absolute remove-row" style="top: 10px; right: 10px; z-index: 10; border-radius: 8px;" title="Remove Test"><i class="fa fa-trash"></i></button>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4 col-sm-6">
                            <label for="field_1110"  class="form-label text-muted fs-11 fw-bold text-uppercase mb-1" style="font-size:11px;">Master Category</label>
                            <div class="input-group flex-nowrap">
                                <select class="form-select report-category-select" name="test_category[]" autocomplete="off" id="field_1110">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->name }}" data-id="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-success btn-sm btn-add-report-category" title="Add Category" style="padding: 0.25rem 0.5rem;"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-primary btn-sm btn-edit-report-category" title="Edit Category" style="padding: 0.25rem 0.5rem;"><i class="fa fa-edit"></i></button>
                                <button type="button" class="btn btn-info btn-sm btn-view-report-category" title="View Category" style="padding: 0.25rem 0.5rem;"><i class="fa fa-eye"></i></button>
                                <button type="button" class="btn btn-danger btn-sm btn-delete-report-category" title="Delete Category" style="padding: 0.25rem 0.5rem;"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label for="field_1111"  class="form-label text-muted fs-11 fw-bold text-uppercase mb-1" style="font-size:11px;">Sub Category</label>
                            <div class="input-group flex-nowrap">
                                <select class="form-select report-subcategory-select" name="test_subcategory[]" autocomplete="off" id="field_1111">
                                    <option value="">-- Select Sub Category --</option>
                                    @foreach($subCategories as $sub)
                                        <option value="{{ $sub->name }}" data-id="{{ $sub->id }}">{{ $sub->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-success btn-sm btn-add-report-subcategory" title="Add Sub-Category" style="padding: 0.25rem 0.5rem;"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-primary btn-sm btn-edit-report-subcategory" title="Edit Sub-Category" style="padding: 0.25rem 0.5rem;"><i class="fa fa-edit"></i></button>
                                <button type="button" class="btn btn-info btn-sm btn-view-report-subcategory" title="View Sub-Category" style="padding: 0.25rem 0.5rem;"><i class="fa fa-eye"></i></button>
                                <button type="button" class="btn btn-danger btn-sm btn-delete-report-subcategory" title="Delete Sub-Category" style="padding: 0.25rem 0.5rem;"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label for="field_1112"  class="form-label text-primary fs-11 fw-bold text-uppercase mb-1" style="font-size:11px;">Parameter / Test Name</label>
                            <div class="input-group flex-nowrap">
                                <select class="form-select test-selector-dynamic border-primary shadow-none" name="test_name[]" autocomplete="off" id="field_1112">
                                    <option value="">-- Select Test --</option>
                                    @foreach($tests as $test)
                                        <option value="{{ $test->name }}" 
                                            data-id="{{ $test->id }}"
                                            data-price="{{ $test->price }}"
                                            data-unit="{{ $test->parameter->unit ?? '' }}"
                                            data-male-ref="{{ $test->parameter->male_reference ?? '' }}"
                                            data-female-ref="{{ $test->parameter->female_reference ?? '' }}"
                                            data-male-min="{{ $test->parameter->male_min ?? '' }}"
                                            data-male-max="{{ $test->parameter->male_max ?? '' }}"
                                            data-female-min="{{ $test->parameter->female_min ?? '' }}"
                                            data-female-max="{{ $test->parameter->female_max ?? '' }}"
                                            data-critical-low="{{ $test->parameter->critical_low ?? '' }}"
                                            data-critical-high="{{ $test->parameter->critical_high ?? '' }}"
                                            data-reference-intervals='{{ $test->referenceIntervals->map->only(['gender', 'age_min', 'age_max', 'reference_text', 'min_value', 'max_value'])->values()->toJson() }}'
                                            data-is-immunoassay="{{ $test->parameter->is_immunoassay ?? 0 }}"
                                            data-bio-ref="{{ $test->parameter->biological_reference ?? '' }}"
                                            data-normal="{{ $test->description }}">{{ $test->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-success btn-sm btn-add-report-test" title="Add Parameter" style="padding: 0.25rem 0.5rem;"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-primary btn-sm btn-edit-report-test" title="Edit Parameter" style="padding: 0.25rem 0.5rem;"><i class="fa fa-edit"></i></button>
                                <button type="button" class="btn btn-info btn-sm btn-view-report-test" title="View Parameter Details" style="padding: 0.25rem 0.5rem;"><i class="fa fa-eye"></i></button>
                                <button type="button" class="btn btn-danger btn-sm btn-delete-report-test" title="Delete Parameter" style="padding: 0.25rem 0.5rem;"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label for="field_1113"  class="form-label text-success fs-11 fw-bold text-uppercase mb-1" style="font-size:11px;">Observed Value</label>
                            <div class="input-group flex-nowrap">
                                <select class="form-select report-observed-select" name="observed_value[]" autocomplete="off" id="field_1113">
                                    <option value="">-- Select Observed --</option>
                                    @foreach($templates as $tmpl)
                                        <option value="{{ $tmpl->name }}" data-id="{{ $tmpl->id }}">{{ $tmpl->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-success btn-sm btn-add-observed" title="Add Observed" style="padding: 0.25rem 0.5rem;"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-primary btn-sm btn-edit-observed" title="Edit Observed" style="padding: 0.25rem 0.5rem;"><i class="fa fa-edit"></i></button>
                                <button type="button" class="btn btn-info btn-sm btn-view-observed" title="View Observed Details" style="padding: 0.25rem 0.5rem;"><i class="fa fa-eye"></i></button>
                                <button type="button" class="btn btn-danger btn-sm btn-delete-observed" title="Delete Observed" style="padding: 0.25rem 0.5rem;"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label for="field_1114"  class="form-label text-muted fs-11 fw-bold text-uppercase mb-1" style="font-size:11px;">Unit</label>
                            <div class="input-group flex-nowrap">
                                <select class="form-select report-unit-select" name="test_unit[]" autocomplete="off" id="field_1114">
                                    <option value="">-- Select Unit --</option>
                                    @foreach($units as $u)
                                        <option value="{{ $u->name }}" data-id="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-success btn-sm btn-add-report-unit" title="Add Unit" style="padding: 0.25rem 0.5rem;"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-primary btn-sm btn-edit-report-unit" title="Edit Unit" style="padding: 0.25rem 0.5rem;"><i class="fa fa-edit"></i></button>
                                <button type="button" class="btn btn-info btn-sm btn-view-report-unit" title="View Unit" style="padding: 0.25rem 0.5rem;"><i class="fa fa-eye"></i></button>
                                <button type="button" class="btn btn-danger btn-sm btn-delete-report-unit" title="Delete Unit" style="padding: 0.25rem 0.5rem;"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label for="field_1115"  class="form-label text-info fs-11 fw-bold text-uppercase mb-1" style="font-size:11px;">Referral Range</label>
                            <div class="input-group flex-nowrap">
                                <select class="form-select normal-val-dynamic" name="normal_value[]" autocomplete="off" id="field_1115">
                                    <option value="">-- Select Reference --</option>
                                    @foreach($referenceTemplates as $ref)
                                        <option value="{{ $ref->name }}" data-id="{{ $ref->id }}">{{ $ref->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-success btn-sm btn-add-reference" title="Add Reference" style="padding: 0.25rem 0.5rem;"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-primary btn-sm btn-edit-reference" title="Edit Reference" style="padding: 0.25rem 0.5rem;"><i class="fa fa-edit"></i></button>
                                <button type="button" class="btn btn-info btn-sm btn-view-reference" title="View Reference" style="padding: 0.25rem 0.5rem;"><i class="fa fa-eye"></i></button>
                                <button type="button" class="btn btn-danger btn-sm btn-delete-reference" title="Delete Reference" style="padding: 0.25rem 0.5rem;"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label for="field_1116"  class="form-label text-warning fs-11 fw-bold text-uppercase mb-1" style="font-size:11px;">Flag</label>
                            <div class="input-group flex-nowrap">
                                <select class="form-select flag-selector" name="test_flag[]" autocomplete="off" id="field_1116">
                                    <option value="">-- Select Flag --</option>
                                    @foreach($flagTemplates as $flg)
                                        <option value="{{ $flg->name }}" data-id="{{ $flg->id }}">{{ $flg->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-success btn-sm btn-add-flag" title="Add Flag" style="padding: 0.25rem 0.5rem;"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-primary btn-sm btn-edit-flag" title="Edit Flag" style="padding: 0.25rem 0.5rem;"><i class="fa fa-edit"></i></button>
                                <button type="button" class="btn btn-info btn-sm btn-view-flag" title="View Flag" style="padding: 0.25rem 0.5rem;"><i class="fa fa-eye"></i></button>
                                <button type="button" class="btn btn-danger btn-sm btn-delete-flag" title="Delete Flag" style="padding: 0.25rem 0.5rem;"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-12">
                            <label for="field_1117"  class="form-label text-dark fs-11 fw-bold text-uppercase mb-1" style="font-size:11px;">Normal Range</label>
                            <div class="input-group flex-nowrap">
                                <select class="form-select bio-val-dynamic" name="biological_reference[]" autocomplete="off" id="field_1117">
                                    <option value="">-- Select Range --</option>
                                    @foreach($referenceTemplates as $ref)
                                        <option value="{{ $ref->name }}" data-id="{{ $ref->id }}">{{ $ref->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-success btn-sm btn-add-reference" title="Add Reference" style="padding: 0.25rem 0.5rem;"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-primary btn-sm btn-edit-reference" title="Edit Reference" style="padding: 0.25rem 0.5rem;"><i class="fa fa-edit"></i></button>
                                <button type="button" class="btn btn-info btn-sm btn-view-reference" title="View Reference" style="padding: 0.25rem 0.5rem;"><i class="fa fa-eye"></i></button>
                                <button type="button" class="btn btn-danger btn-sm btn-delete-reference" title="Delete Reference" style="padding: 0.25rem 0.5rem;"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

          // Auto-fill Normal Value from Select & Handle Auto-calc Logic
          $(document).on('change', '.test-selector-dynamic', function() {
              let selected = $(this).find(':selected');
	              let row = $(this).closest('.test-item-row');
	              let modal = $(this).closest('.modal');
	              let gender = modal.find('select[name="patient_id"] :selected').attr('data-gender') || 'M/F';
	              let age = parseInt(modal.find('select[name="patient_id"] :selected').attr('data-age'), 10);
	              
	              // Set Unit
	              row.find('select[name="test_unit[]"]').val(selected.attr('data-unit') || '');
	              
	              // Set Reference Interval based on Gender
	              let ageInterval = getMatchingReferenceInterval(selected, gender, age);
	              let ref = ageInterval ? ageInterval.reference_text : (gender === 'Male' ? selected.attr('data-male-ref') : selected.attr('data-female-ref'));
	              if (!ref) ref = selected.attr('data-male-ref') || selected.attr('data-female-ref') || selected.attr('data-normal') || '';
              
              row.find('.normal-val-dynamic').val(ref);
              
              // Keep legacy JSON field filled for older reports while printing the normal value column.
              let masterBio = selected.attr('data-bio-ref');
              row.find('.bio-val-dynamic').val(masterBio || ref);
              
               // Trigger calculation if value exists
               row.find('.report-observed-select').trigger('change');
          });

          $(document).on('change', '.normal-val-dynamic', function() {
              let val = $(this).val();
              $(this).closest('.test-item-row').find('.bio-val-dynamic').val(val);
          });

          // Keep hidden flag values compatible with older saved reports.
           $(document).on('change', '.report-observed-select', function() {
              let val = parseFloat($(this).val());
	              let row = $(this).closest('.test-item-row');
	              let selected = row.find('.test-selector-dynamic :selected');
	              let modal = $(this).closest('.modal');
	              let gender = modal.find('select[name="patient_id"] :selected').attr('data-gender') || 'Male';
	              let age = parseInt(modal.find('select[name="patient_id"] :selected').attr('data-age'), 10);
	              let flagSelector = row.find('.flag-selector');
              
              if (isNaN(val)) {
                  flagSelector.val('');
                  return;
              }

	              let isImmuno = selected.attr('data-is-immunoassay') == 1;
	              let criticalLow = parseFloat(selected.attr('data-critical-low'));
	              let criticalHigh = parseFloat(selected.attr('data-critical-high'));
	              let ageInterval = getMatchingReferenceInterval(selected, gender, age);
	              let min = ageInterval ? ageInterval.min_value : (gender === 'Male' ? selected.attr('data-male-min') : selected.attr('data-female-min'));
	              let max = ageInterval ? ageInterval.max_value : (gender === 'Male' ? selected.attr('data-male-max') : selected.attr('data-female-max'));

	              if (!isNaN(criticalLow) && val <= criticalLow) {
	                  setSelectValueWithDefault(flagSelector, 'C');
	                  return;
	              }

	              if (!isNaN(criticalHigh) && val >= criticalHigh) {
	                  setSelectValueWithDefault(flagSelector, 'C');
	                  return;
	              }

              if (isImmuno) {
                  // Immunoassay logic: <0.9 Neg, 0.9-1.1 Bord, >1.1 Pos
                  if (val < 0.9) setSelectValueWithDefault(flagSelector, 'N');
                  else if (val >= 0.9 && val <= 1.1) setSelectValueWithDefault(flagSelector, 'B');
                  else if (val > 1.1) setSelectValueWithDefault(flagSelector, 'P');
              } else if (min !== undefined && max !== undefined) {
                  // Standard Min-Max logic
	                  if (val < min) setSelectValueWithDefault(flagSelector, 'L');
	                  else if (val > max) setSelectValueWithDefault(flagSelector, 'H');
	                  else setSelectValueWithDefault(flagSelector, 'N');
	              }
	          });

	          function getMatchingReferenceInterval(selected, gender, age) {
	              let intervals = JSON.parse(selected.attr('data-reference-intervals') || '[]');
	              if (!Array.isArray(intervals) || isNaN(age)) return null;
	              gender = (gender || '').toLowerCase();
	              return intervals
	                  .filter(interval => {
	                      let intervalGender = (interval.gender || '').toLowerCase();
	                      let min = interval.age_min === null ? 0 : parseInt(interval.age_min, 10);
	                      let max = interval.age_max === null ? 999 : parseInt(interval.age_max, 10);
	                      return (!intervalGender || intervalGender === gender) && age >= min && age <= max;
	                  })
	                  .sort((a, b) => parseInt(b.age_min || 0, 10) - parseInt(a.age_min || 0, 10))[0] || null;
	          }

          function calculateCBC(containerSelector) {
              let rbc = null;
              let hb = null;
              let hct = null;
              
              let rbcRow = null;
              let hbRow = null;
              let hctRow = null;
              
              let mcvRow = null;
              let mchRow = null;
              let mchcRow = null;
              
              // Iterate over all rows in this container
              $(containerSelector).find('.test-item-row').each(function() {
                  let testSelect = $(this).find('.test-selector-dynamic');
                  let testName = (testSelect.val() || '').toLowerCase().trim();
                  let observedVal = parseFloat($(this).find('.report-observed-select').val());
                  
                  // Check test names
                  if (testName === 'rbc' || testName === 'rbc count' || testName.indexOf('red blood cell') > -1) {
                      rbc = observedVal;
                      rbcRow = $(this);
                  } else if (testName === 'hemoglobin' || testName === 'hb' || testName === 'hgb') {
                      hb = observedVal;
                      hbRow = $(this);
                  } else if (testName === 'pcv' || testName === 'hct' || testName === 'hematocrit' || testName === 'packed cell volume') {
                      hct = observedVal;
                      hctRow = $(this);
                  } else if (testName === 'mcv' || testName.indexOf('mean corpuscular volume') > -1) {
                      mcvRow = $(this);
                  } else if (testName === 'mch' || testName.indexOf('mean corpuscular hemoglobin') > -1 && testName.indexOf('concentration') === -1) {
                      mchRow = $(this);
                  } else if (testName === 'mchc' || testName.indexOf('mchc') > -1 || testName.indexOf('mean corpuscular hemoglobin concentration') > -1) {
                      mchcRow = $(this);
                  }
              });
              
              if (window._isCalculatingCBC) return;
              window._isCalculatingCBC = true;
              
              try {
                  // MCV = (HCT / RBC) * 10
                  if (mcvRow && rbc !== null && hct !== null && !isNaN(rbc) && !isNaN(hct) && rbc > 0) {
                      let mcv = ((hct / rbc) * 10).toFixed(1);
                      let observedSelect = mcvRow.find('.report-observed-select');
                      setSelectValueWithDefault(observedSelect, mcv);
                      observedSelect.trigger('change');
                  }
                  
                  // MCH = (Hb / RBC) * 10
                  if (mchRow && rbc !== null && hb !== null && !isNaN(rbc) && !isNaN(hb) && rbc > 0) {
                      let mch = ((hb / rbc) * 10).toFixed(1);
                      let observedSelect = mchRow.find('.report-observed-select');
                      setSelectValueWithDefault(observedSelect, mch);
                      observedSelect.trigger('change');
                  }
                  
                  // MCHC = (Hb / HCT) * 100
                  if (mchcRow && hb !== null && hct !== null && !isNaN(hb) && !isNaN(hct) && hct > 0) {
                      let mchc = ((hb / hct) * 100).toFixed(1);
                      let observedSelect = mchcRow.find('.report-observed-select');
                      setSelectValueWithDefault(observedSelect, mchc);
                      observedSelect.trigger('change');
                  }
              } finally {
                  window._isCalculatingCBC = false;
              }
          }

          // Trigger CBC calculation when observed values change or test parameter is selected
          $(document).on('change', '.report-observed-select, .test-selector-dynamic', function() {
              let container = $(this).closest('#dynamic-tests-container').length ? '#dynamic-tests-container' : '#edit-dynamic-tests-container';
              calculateCBC(container);
          });

          // Re-trigger calculation when patient (gender) changes
          $(document).on('change', 'select[name="patient_id"]', function() {
              let modal = $(this).closest('.modal');
              modal.find('.test-selector-dynamic').trigger('change');
          });

          function updateSignaturePinRequirement(select) {
              const modal = $(select).closest('.modal');
              const pinInput = modal.find('.signature-pin-input');
              const hasSignature = !!$(select).val();
              pinInput.prop('required', hasSignature);
              pinInput.attr('placeholder', hasSignature ? 'Required' : 'PIN');
              if (!hasSignature) {
                  pinInput.val('');
              }
          }

          $(document).on('change', '.report-signature-select', function() {
              updateSignaturePinRequirement(this);
          });

          $('.report-signature-select').each(function() {
              updateSignaturePinRequirement(this);
          });

          function setSelectValueWithDefault(select, value) {
              if (value === undefined || value === null) {
                  select.val('');
                  return;
              }
              if (select.is('input')) {
                  select.val(value);
                  return;
              }
              let strVal = String(value);
              let found = false;
              select.find('option').each(function() {
                  if ($(this).val() === strVal) {
                      found = true;
                      return false;
                  }
              });
              if (!found) {
                  select.append($('<option>', {
                      value: strVal,
                      text: strVal
                  }));
              }
              select.val(strVal);
          }

          function initDynamicSelect2() {
              // Revert all select elements inside the reports modals to standard native HTML selects
              $('#modal-add-report select, #modal-edit-report select').each(function() {
                  if ($(this).hasClass('select2-hidden-accessible')) {
                      $(this).select2('destroy');
                  }
              });
          }

          // Modal clearing logic to fix 'titles not clearing'
          $('#modal-add-report').on('hidden.bs.modal', function() {
              $('#form-add-report')[0].reset();
              $('#dynamic-tests-container').empty().append(trTemplate);
              initDynamicSelect2();
          });

          $('#modal-add-report').on('shown.bs.modal', function() {
              initDynamicSelect2();
          });

          $('#modal-edit-report').on('hidden.bs.modal', function() {
              $('#form-edit-report')[0].reset();
              $('#edit-dynamic-tests-container').empty();
          });

          $('#modal-edit-report').on('shown.bs.modal', function() {
              initDynamicSelect2();
          });
          
          initDynamicSelect2();

          $('#btn-add-test-row, #btn-add-edit-test-row').click(function() {
              let target = $(this).attr('id') === 'btn-add-test-row' ? '#dynamic-tests-container' : '#edit-dynamic-tests-container';
              $(target).append(trTemplate);
              initDynamicSelect2();
          });

          $(document).on('click', '.remove-row', function() {
              $(this).closest('.test-item-row').remove();
          });

          // =============================================
          // DOCTOR SELECT MANAGEMENT (for Reports)
          // =============================================
          function fetchReportDoctors(selectedValue = null) {
              $.get("{{ route('doctors.suggestions') }}", function(data) {
                  let options = '<option value="">-- Select Doctor --</option><option value="Self">Self</option>';
                  (Array.isArray(data) ? data : (data.data || [])).forEach(function(doctor) {
                      let qual = doctor.qualification ? doctor.qualification : '';
                      options += `<option value="${doctor.name}" data-id="${doctor.id}" data-phone="${doctor.phone || ''}" data-email="${doctor.email || ''}" data-qualification="${qual}">${doctor.name}${qual ? ' (' + qual + ')' : ''}</option>`;
                  });
                  $('.report-doctor-select').html(options);
                  if (selectedValue) {
                      $('.report-doctor-select').val(selectedValue).trigger('change');
                  } else {
                      $('.report-doctor-select').trigger('change');
                  }
              });
          }

          // Open Add Doctor modal from report
          $(document).on('click', '.btn-add-report-doctor', function() {
              $('#modal-add-report-doctor').modal('show');
          });

          // Open Edit Doctor modal from report
          $(document).on('click', '.btn-edit-report-doctor', function() {
              let select = $(this).siblings('.report-doctor-select');
              let selectedOption = select.find('option:selected');
              let docId = selectedOption.attr('data-id');
              if (!docId) { alert('Please select a valid doctor to edit.'); return; }
              $('#edit-report-doc-id').val(docId);
              $('#edit-report-doc-name').val(selectedOption.val());
              $('#edit-report-doc-qualification').val(selectedOption.attr('data-qualification'));
              $('#edit-report-doc-phone').val(selectedOption.attr('data-phone'));
              $('#edit-report-doc-email').val(selectedOption.attr('data-email'));
              $('#modal-edit-report-doctor').modal('show');
          });

          $('#btn-save-report-doctor').click(function() {
              let formData = $('#form-add-report-doctor').serialize();
              $.post("{{ route('doctors.store') }}", formData, function(response) {
                  alert(response.success);
                  $('#modal-add-report-doctor').modal('hide');
                  $('#form-add-report-doctor')[0].reset();
                  fetchReportDoctors(response.doctor.name);
              }).fail(function(xhr) {
                  alert('Error: ' + (xhr.responseJSON.message || 'Failed to save doctor.'));
              });
          });

          $('#btn-update-report-doctor').click(function() {
              let docId = $('#edit-report-doc-id').val();
              $.ajax({
                  url: "/doctors/" + docId,
                  type: 'PUT',
                  data: $('#form-edit-report-doctor').serialize(),
                  success: function(response) {
                      alert(response.success);
                      $('#modal-edit-report-doctor').modal('hide');
                      fetchReportDoctors(response.doctor.name);
                  },
                  error: function(xhr) {
                      alert('Error: ' + (xhr.responseJSON.message || 'Failed to update doctor.'));
                  }
              });
          });

          fetchReportDoctors();
          // =============================================

          // =============================================
          // STARTUP: Load all dynamic dropdowns from DB
          // =============================================
          fetchReportCategories();
          fetchReportSubCategories();
          fetchReportTests();
          fetchReportUnits();
          fetchReportReferences();
          fetchReportObserved();
          fetchReportFlags();
          // =============================================

          // =============================================
          // CATEGORY SELECT MANAGEMENT
          // =============================================
          function fetchReportCategories(selectedValue = null) {
              $.get("{{ route('categories.index') }}", function(data) {
                  let options = '<option value="">-- Select Category --</option>';
                  let modalOptions = '<option value="">-- Select Category --</option>';
                  (Array.isArray(data) ? data : (data.data || [])).forEach(function(cat) {
                      options += `<option value="${cat.name}" data-id="${cat.id}">${cat.name}</option>`;
                      modalOptions += `<option value="${cat.id}">${cat.name}</option>`;
                  });
                  $('.report-category-select').each(function() {
                      let currentVal = $(this).val();
                      $(this).html(options);
                      if (selectedValue && $(this).hasClass('active-category-select')) {
                          $(this).val(selectedValue).removeClass('active-category-select').trigger('change');
                      } else {
                          $(this).val(currentVal).trigger('change');
                      }
                  });
                  
                  // Also update subcategory modals!
                  let addSubCatSelect = $('#add-report-sub-category-id');
                  let addSubCatVal = addSubCatSelect.val();
                  addSubCatSelect.html(modalOptions).val(addSubCatVal);
                  
                  let editSubCatSelect = $('#edit-report-sub-category-id');
                  let editSubCatVal = editSubCatSelect.val();
                  editSubCatSelect.html(modalOptions).val(editSubCatVal);
              });
          }

          $(document).on('click', '.btn-add-report-category', function() {
              $('.report-category-select').removeClass('active-category-select');
              $(this).siblings('.report-category-select').addClass('active-category-select');
              $('#modal-add-report-category').modal('show');
          });

          $(document).on('click', '.btn-edit-report-category', function() {
              let select = $(this).siblings('.report-category-select');
              let selectedOption = select.find('option:selected');
              let catId = selectedOption.attr('data-id');
              if (!catId) { alert('Please select a valid category to edit.'); return; }
              $('.report-category-select').removeClass('active-category-select');
              select.addClass('active-category-select');
              
              $('#edit-report-cat-id').val(catId);
              $('#edit-report-cat-name').val(selectedOption.val());
              $('#modal-edit-report-category').modal('show');
          });

          $('#btn-save-report-category').click(function() {
              let formData = $('#form-add-report-category').serialize();
              $.post("{{ route('categories.store') }}", formData, function(response) {
                  alert(response.success);
                  $('#modal-add-report-category').modal('hide');
                  $('#form-add-report-category')[0].reset();
                  fetchReportCategories(response.category.name);
              }).fail(function(xhr) {
                  alert('Error: ' + (xhr.responseJSON.message || 'Failed to save category.'));
              });
          });

          $('#btn-update-report-category').click(function() {
              let catId = $('#edit-report-cat-id').val();
              $.ajax({
                  url: "/categories/" + catId,
                  type: 'PUT',
                  data: $('#form-edit-report-category').serialize(),
                  success: function(response) {
                      alert(response.success);
                      $('#modal-edit-report-category').modal('hide');
                      fetchReportCategories(response.category.name);
                  },
                  error: function(xhr) {
                      alert('Error: ' + (xhr.responseJSON.message || 'Failed to update category.'));
                  }
              });
          });
          // =============================================

          // =============================================
          // SUBCATEGORY SELECT MANAGEMENT
          // =============================================
          function fetchReportSubCategories(selectedValue = null) {
              $.get("{{ route('sub-categories.index') }}", function(data) {
                  let options = '<option value="">-- Select Sub Category --</option>';
                  (Array.isArray(data) ? data : (data.data || [])).forEach(function(sub) {
                      options += `<option value="${sub.name}" data-id="${sub.id}">${sub.name}</option>`;
                  });
                  $('.report-subcategory-select').each(function() {
                      let currentVal = $(this).val();
                      $(this).html(options);
                      if (selectedValue && $(this).hasClass('active-subcategory-select')) {
                          $(this).val(selectedValue).removeClass('active-subcategory-select').trigger('change');
                      } else {
                          $(this).val(currentVal).trigger('change');
                      }
                  });
              });
          }

          $(document).on('click', '.btn-add-report-subcategory', function() {
              $('.report-subcategory-select').removeClass('active-subcategory-select');
              $(this).siblings('.report-subcategory-select').addClass('active-subcategory-select');
              
              let catId = $(this).closest('.test-item-row').find('.report-category-select option:selected').attr('data-id');
              $('#add-report-sub-category-id').val(catId || '');
              
              $('#modal-add-report-subcategory').modal('show');
          });

          $(document).on('click', '.btn-edit-report-subcategory', function() {
              let select = $(this).siblings('.report-subcategory-select');
              let selectedOption = select.find('option:selected');
              let subId = selectedOption.attr('data-id');
              if (!subId) { alert('Please select a valid sub-category to edit.'); return; }
              $('.report-subcategory-select').removeClass('active-subcategory-select');
              select.addClass('active-subcategory-select');
              
              let catId = $(this).closest('.test-item-row').find('.report-category-select option:selected').attr('data-id');
              $('#edit-report-sub-category-id').val(catId || '');
              
              $('#edit-report-sub-id').val(subId);
              $('#edit-report-sub-name').val(selectedOption.val());
              $('#modal-edit-report-subcategory').modal('show');
          });

          $('#btn-save-report-subcategory').click(function() {
              let formData = $('#form-add-report-subcategory').serialize();
              $.post("{{ route('sub-categories.store') }}", formData, function(response) {
                  alert(response.success);
                  $('#modal-add-report-subcategory').modal('hide');
                  $('#form-add-report-subcategory')[0].reset();
                  fetchReportSubCategories(response.subCategory.name);
              }).fail(function(xhr) {
                  alert('Error: ' + (xhr.responseJSON.message || 'Failed to save sub-category.'));
              });
          });

          $('#btn-update-report-subcategory').click(function() {
              let subId = $('#edit-report-sub-id').val();
              $.ajax({
                  url: "/sub-categories/" + subId,
                  type: 'PUT',
                  data: $('#form-edit-report-subcategory').serialize(),
                  success: function(response) {
                      alert(response.success);
                      $('#modal-edit-report-subcategory').modal('hide');
                      fetchReportSubCategories(response.subCategory.name);
                  },
                  error: function(xhr) {
                      alert('Error: ' + (xhr.responseJSON.message || 'Failed to update sub-category.'));
                  }
              });
          });
          // =============================================

          // =============================================
          // TEST/PARAMETER SELECT MANAGEMENT
          // =============================================
          function fetchReportTests(selectedValue = null) {
              $.get("{{ route('api.tests') }}", function(data) {
                  let options = '<option value="">-- Select Test --</option>';
                  (Array.isArray(data) ? data : (data.data || [])).forEach(function(test) {
                      let param = test.parameter || {};
                      let refJson = '[]';
                      if(test.reference_intervals) {
                          let refs = test.reference_intervals.map(r => ({
                              gender: r.gender, age_min: r.age_min, age_max: r.age_max, reference_text: r.reference_text, min_value: r.min_value, max_value: r.max_value
                          }));
                          // sanitize quotes
                          refJson = JSON.stringify(refs).replace(/'/g, "&#39;");
                      }
                      options += `<option value="${test.name}" 
                          data-id="${test.id}"
                          data-price="${test.price || 0}"
                          data-unit="${param.unit || ''}"
                          data-male-ref="${param.male_reference || ''}"
                          data-female-ref="${param.female_reference || ''}"
                          data-male-min="${param.male_min || ''}"
                          data-male-max="${param.male_max || ''}"
                          data-female-min="${param.female_min || ''}"
                          data-female-max="${param.female_max || ''}"
                          data-critical-low="${param.critical_low || ''}"
                          data-critical-high="${param.critical_high || ''}"
                          data-reference-intervals='${refJson}'
                          data-is-immunoassay="${param.is_immunoassay || 0}"
                          data-bio-ref="${param.biological_reference || ''}"
                          data-normal="${test.description || ''}">${test.name}</option>`;
                  });
                  options += '<option value="Immunoassay Test">Immunoassay Test (Auto-calc)</option>';

                  $('.test-selector-dynamic').each(function() {
                      let currentVal = $(this).val();
                      $(this).html(options);
                      if (selectedValue && $(this).hasClass('active-test-select')) {
                          $(this).val(selectedValue).removeClass('active-test-select').trigger('change'); // trigger auto-fill
                      } else {
                          $(this).val(currentVal).trigger('change');
                      }
                  });
              });
          }

          $(document).on('click', '.btn-add-report-test', function() {
              $('.test-selector-dynamic').removeClass('active-test-select');
              $(this).siblings('.test-selector-dynamic').addClass('active-test-select');
              $('#modal-add-report-test').modal('show');
          });

          $(document).on('click', '.btn-edit-report-test', function() {
              let select = $(this).siblings('.test-selector-dynamic');
              let selectedOption = select.find('option:selected');
              let testId = selectedOption.attr('data-id');
              if (!testId) { alert('Please select a valid parameter to edit.'); return; }
              $('.test-selector-dynamic').removeClass('active-test-select');
              select.addClass('active-test-select');
              
              $('#edit-report-test-id').val(testId);
              $('#edit-report-test-name').val(selectedOption.val());
              $('#edit-report-test-unit').val(selectedOption.attr('data-unit'));
              $('#edit-report-test-bio').val(selectedOption.attr('data-bio-ref'));
              $('#modal-edit-report-test').modal('show');
          });

          $('#btn-save-report-test').click(function() {
              let formData = $('#form-add-report-test').serialize();
              $.post("{{ route('tests.quick-store') }}", formData, function(response) {
                  alert(response.success);
                  $('#modal-add-report-test').modal('hide');
                  $('#form-add-report-test')[0].reset();
                  fetchReportTests(response.test.name);
              }).fail(function(xhr) {
                  alert('Error: ' + (xhr.responseJSON.message || 'Failed to save parameter.'));
              });
          });

          $('#btn-update-report-test').click(function() {
              let testId = $('#edit-report-test-id').val();
              $.ajax({
                  url: "/tests/quick-update/" + testId,
                  type: 'PUT',
                  data: $('#form-edit-report-test').serialize(),
                  success: function(response) {
                      alert(response.success);
                      $('#modal-edit-report-test').modal('hide');
                      fetchReportTests(response.test.name);
                  },
                  error: function(xhr) {
                      alert('Error: ' + (xhr.responseJSON.message || 'Failed to update parameter.'));
                  }
              });
          });

          // =============================================
          // UNIT SELECT MANAGEMENT
          // =============================================
          function fetchReportUnits(selectedValue = null) {
              $.get("{{ route('units.index') }}", function(data) {
                  let options = '<option value="">-- Select Unit --</option>';
                  (Array.isArray(data) ? data : (data.data || [])).forEach(function(u) {
                      options += `<option value="${u.name}" data-id="${u.id}">${u.name}</option>`;
                  });
                  $('.report-unit-select').each(function() {
                      let currentVal = $(this).val();
                      $(this).html(options);
                      if (selectedValue && $(this).hasClass('active-unit-select')) {
                          $(this).val(selectedValue).removeClass('active-unit-select').trigger('change');
                      } else {
                          $(this).val(currentVal).trigger('change');
                      }
                  });
              });
          }

          $(document).on('click', '.btn-add-report-unit', function() {
              $('.report-unit-select').removeClass('active-unit-select');
              $(this).siblings('.report-unit-select').addClass('active-unit-select');
              $('#modal-add-report-unit').modal('show');
          });

          $(document).on('click', '.btn-edit-report-unit', function() {
              let select = $(this).siblings('.report-unit-select');
              let selectedOption = select.find('option:selected');
              let unitId = selectedOption.attr('data-id');
              if (!unitId) { alert('Please select a valid unit to edit.'); return; }
              $('.report-unit-select').removeClass('active-unit-select');
              select.addClass('active-unit-select');
              
              $('#edit-report-unit-id').val(unitId);
              $('#edit-report-unit-name').val(selectedOption.val());
              $('#modal-edit-report-unit').modal('show');
          });

          $('#btn-save-report-unit').click(function() {
              let formData = $('#form-add-report-unit').serialize();
              $.post("{{ route('units.store') }}", formData, function(response) {
                  alert(response.success);
                  $('#modal-add-report-unit').modal('hide');
                  $('#form-add-report-unit')[0].reset();
                  fetchReportUnits(response.unit.name);
              }).fail(function(xhr) {
                  alert('Error: ' + (xhr.responseJSON.message || 'Failed to save unit.'));
              });
          });

          $('#btn-update-report-unit').click(function() {
              let unitId = $('#edit-report-unit-id').val();
              $.ajax({
                  url: "/units/" + unitId,
                  type: 'PUT',
                  data: $('#form-edit-report-unit').serialize(),
                  success: function(response) {
                      alert(response.success);
                      $('#modal-edit-report-unit').modal('hide');
                      fetchReportUnits(response.unit.name);
                  },
                  error: function(xhr) {
                      alert('Error: ' + (xhr.responseJSON.message || 'Failed to update unit.'));
                  }
              });
          });
          // =============================================
          function fetchReportReferences(selectedValue = null) {
              $.get("{{ route('reference-templates.index') }}", function(data) {
                  let options = '<option value="">-- Select Reference --</option>';
                  (Array.isArray(data) ? data : (data.data || [])).forEach(function(ref) {
                      options += `<option value="${ref.name}" data-id="${ref.id}">${ref.name}</option>`;
                  });
                  $('.normal-val-dynamic, .bio-val-dynamic').each(function() {
                      let currentVal = $(this).val();
                      let currentSelectedId = $(this).find('option:selected').attr('data-id');
                      $(this).html(options);
                      if (selectedValue && $(this).hasClass('active-reference-select')) {
                          $(this).val(selectedValue).removeClass('active-reference-select').trigger('change');
                      } else if (currentSelectedId) {
                          let opt = $(this).find(`option[data-id="${currentSelectedId}"]`);
                          if (opt.length) {
                              $(this).val(opt.val()).trigger('change');
                          }
                      } else {
                          $(this).val(currentVal).trigger('change');
                      }
                  });
              });
          }

          function fetchReportObserved(selectedValue = null) {
              $.get("{{ route('result-templates.index') }}", function(data) {
                  let options = '<option value="">-- Select Observed --</option>';
                  (Array.isArray(data) ? data : (data.data || [])).forEach(function(tmpl) {
                      options += `<option value="${tmpl.name}" data-id="${tmpl.id}">${tmpl.name}</option>`;
                  });
                  $('.report-observed-select').each(function() {
                      let currentVal = $(this).val();
                      let currentSelectedId = $(this).find('option:selected').attr('data-id');
                      $(this).html(options);
                      if (selectedValue && $(this).hasClass('active-observed-select')) {
                          $(this).val(selectedValue).removeClass('active-observed-select').trigger('change');
                      } else if (currentSelectedId) {
                          let opt = $(this).find(`option[data-id="${currentSelectedId}"]`);
                          if (opt.length) {
                              $(this).val(opt.val()).trigger('change');
                          }
                      } else {
                          $(this).val(currentVal).trigger('change');
                      }
                  });
              });
          }

          function fetchReportFlags(selectedValue = null) {
              $.get("{{ route('flag-templates.index') }}", function(data) {
                  let options = '<option value="">-- Select Reference --</option>';
                  (Array.isArray(data) ? data : (data.data || [])).forEach(function(flg) {
                      options += `<option value="${flg.name}" data-id="${flg.id}">${flg.name}</option>`;
                  });
                  $('.flag-selector').each(function() {
                      let currentVal = $(this).val();
                      let currentSelectedId = $(this).find('option:selected').attr('data-id');
                      $(this).html(options);
                      if (selectedValue && $(this).hasClass('active-flag-select')) {
                          $(this).val(selectedValue).removeClass('active-flag-select').trigger('change');
                      } else if (currentSelectedId) {
                          let opt = $(this).find(`option[data-id="${currentSelectedId}"]`);
                          if (opt.length) {
                              $(this).val(opt.val()).trigger('change');
                          }
                      } else {
                          $(this).val(currentVal).trigger('change');
                      }
                  });
              });
          }

          // =============================================
          // OBSERVED TEMPLATE MANAGEMENT
          // =============================================
          $(document).on('click', '.btn-add-observed', function() {
              $('.report-observed-select').removeClass('active-observed-select');
              let select = $(this).closest('.test-item-row').find('.report-observed-select');
              select.addClass('active-observed-select');
              $('#modal-add-report-observed').modal('show');
          });

          $(document).on('click', '.btn-edit-observed', function() {
              let select = $(this).closest('.test-item-row').find('.report-observed-select');
              let selectedOption = select.find('option:selected');
              let observedName = selectedOption.val();
              if (!observedName) { alert('Please select a valid observed value to edit.'); return; }
              $('.report-observed-select').removeClass('active-observed-select');
              select.addClass('active-observed-select');
              
              let observedId = selectedOption.attr('data-id');
              $('#edit-report-observed-id').val(observedId);
              $('#edit-report-observed-name').val(observedName);
              $('#modal-edit-report-observed').modal('show');
          });

          $('#btn-save-report-observed').click(function() {
              let formData = $('#form-add-report-observed').serialize();
              let nameVal = $('#form-add-report-observed input[name="name"]').val().trim();
              $.post("{{ route('result-templates.store') }}", formData, function(response) {
                  alert(response.success);
                  $('#modal-add-report-observed').modal('hide');
                  $('#form-add-report-observed')[0].reset();
                  fetchReportObserved(nameVal);
              }).fail(function(xhr) {
                  alert('Error: ' + (xhr.responseJSON.message || 'Failed to save observed template.'));
              });
          });

          $('#btn-update-report-observed').click(function() {
              let observedId = $('#edit-report-observed-id').val();
              let nameVal = $('#edit-report-observed-name').val().trim();
              $.ajax({
                  url: "/result-templates/" + observedId,
                  type: 'PUT',
                  data: $('#form-edit-report-observed').serialize(),
                  success: function(response) {
                      alert(response.success);
                      $('#modal-edit-report-observed').modal('hide');
                      $('#form-edit-report-observed')[0].reset();
                      fetchReportObserved(nameVal);
                  },
                  error: function(xhr) {
                      alert('Error: ' + (xhr.responseJSON.message || 'Failed to update observed template.'));
                  }
              });
          });
          // =============================================

          // =============================================
          // REFERENCE TEMPLATE MANAGEMENT
          // =============================================
          $(document).on('click', '.btn-add-reference', function() {
              $('.normal-val-dynamic, .bio-val-dynamic').removeClass('active-reference-select');
              let select = $(this).closest('.input-group').find('select');
              select.addClass('active-reference-select');
              $('#modal-add-report-reference').modal('show');
          });

          $(document).on('click', '.btn-edit-reference', function() {
              let select = $(this).closest('.test-item-row').find('.normal-val-dynamic');
              let selectedOption = select.find('option:selected');
              let referenceName = selectedOption.val();
              if (!referenceName) { alert('Please select a valid reference value to edit.'); return; }
              $('.normal-val-dynamic').removeClass('active-reference-select');
              select.addClass('active-reference-select');
              
              let referenceId = selectedOption.attr('data-id');
              $('#edit-report-reference-id').val(referenceId);
              $('#edit-report-reference-name').val(referenceName);
              $('#modal-edit-report-reference').modal('show');
          });

          $('#btn-save-report-reference').click(function() {
              let formData = $('#form-add-report-reference').serialize();
              let nameVal = $('#form-add-report-reference input[name="name"]').val().trim();
              $.post("{{ route('reference-templates.store') }}", formData, function(response) {
                  alert(response.success);
                  $('#modal-add-report-reference').modal('hide');
                  $('#form-add-report-reference')[0].reset();
                  fetchReportReferences(nameVal);
              }).fail(function(xhr) {
                  alert('Error: ' + (xhr.responseJSON.message || 'Failed to save reference template.'));
              });
          });

          $('#btn-update-report-reference').click(function() {
              let referenceId = $('#edit-report-reference-id').val();
              let nameVal = $('#edit-report-reference-name').val().trim();
              $.ajax({
                  url: "/reference-templates/" + referenceId,
                  type: 'PUT',
                  data: $('#form-edit-report-reference').serialize(),
                  success: function(response) {
                      alert(response.success);
                      $('#modal-edit-report-reference').modal('hide');
                      $('#form-edit-report-reference')[0].reset();
                      fetchReportReferences(nameVal);
                  },
                  error: function(xhr) {
                      alert('Error: ' + (xhr.responseJSON.message || 'Failed to update reference template.'));
                  }
              });
          });

          $(document).on('click', '.btn-delete-reference', function() {
              let select = $(this).closest('.test-item-row').find('.normal-val-dynamic');
              let selectedOption = select.find('option:selected');
              let referenceId = selectedOption.attr('data-id');
              let referenceName = selectedOption.val();
              if (!referenceId) { alert('Please select a valid reference template to delete.'); return; }
              
              if (confirm('Are you sure you want to delete the reference template "' + referenceName + '"?')) {
                  $.ajax({
                      url: "/reference-templates/" + referenceId,
                      type: 'DELETE',
                      success: function(response) {
                          alert(response.success || 'Reference template deleted successfully!');
                          fetchReportReferences();
                      },
                      error: function(xhr) {
                          alert('Error: ' + (xhr.responseJSON?.message || 'Failed to delete reference template.'));
                      }
                  });
              }
          });
          // =============================================

          // =============================================
          // FLAG TEMPLATE MANAGEMENT
          // =============================================
          $(document).on('click', '.btn-add-flag', function() {
              $('.flag-selector').removeClass('active-flag-select');
              let select = $(this).closest('.test-item-row').find('.flag-selector');
              select.addClass('active-flag-select');
              $('#modal-add-report-flag').modal('show');
          });

          $(document).on('click', '.btn-edit-flag', function() {
              let select = $(this).closest('.test-item-row').find('.flag-selector');
              let selectedOption = select.find('option:selected');
              let flagName = selectedOption.val();
              if (!flagName) { alert('Please select a valid flag to edit.'); return; }
              $('.flag-selector').removeClass('active-flag-select');
              select.addClass('active-flag-select');
              
              let flagId = selectedOption.attr('data-id');
              $('#edit-report-flag-id').val(flagId);
              $('#edit-report-flag-name').val(flagName);
              $('#modal-edit-report-flag').modal('show');
          });

          $('#btn-save-report-flag').click(function() {
              let formData = $('#form-add-report-flag').serialize();
              let nameVal = $('#form-add-report-flag input[name="name"]').val().trim();
              $.post("{{ route('flag-templates.store') }}", formData, function(response) {
                  alert(response.success);
                  $('#modal-add-report-flag').modal('hide');
                  $('#form-add-report-flag')[0].reset();
                  fetchReportFlags(nameVal);
              }).fail(function(xhr) {
                  alert('Error: ' + (xhr.responseJSON.message || 'Failed to save flag template.'));
              });
          });

          $('#btn-update-report-flag').click(function() {
              let flagId = $('#edit-report-flag-id').val();
              let nameVal = $('#edit-report-flag-name').val().trim();
              $.ajax({
                  url: "/flag-templates/" + flagId,
                  type: 'PUT',
                  data: $('#form-edit-report-flag').serialize(),
                  success: function(response) {
                      alert(response.success);
                      $('#modal-edit-report-flag').modal('hide');
                      $('#form-edit-report-flag')[0].reset();
                      fetchReportFlags(nameVal);
                  },
                  error: function(xhr) {
                      alert('Error: ' + (xhr.responseJSON.message || 'Failed to update flag template.'));
                  }
              });
          });
          // =============================================

           // Save Report

		  $('#btn-save-report').click(function() {
              if(!$('#form-add-report')[0].checkValidity()) {
                  $('#form-add-report')[0].reportValidity();
                  return;
              }

			  let btn = $(this);
              btn.html('<i class="fa fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
			  
			  $.post("{{ route('reports.store') }}", $('#form-add-report').serialize(), function(response) {
				  alert(response.success);
				  refreshReportsPageData('#modal-add-report', btn, 'Generate Report');
                  $('#form-add-report')[0].reset();
                  $('#dynamic-tests-container').empty();
			  }).fail(function(xhr) {
                  let errorMsg = xhr.responseJSON.message || "Error saving report. Please check all fields.";
				  alert(errorMsg);
                  btn.html('Generate Report').prop('disabled', false);
			  });
		  });

          // Edit Report Load Data
          $(document).on('click', '.btn-edit', function(e) {
              e.preventDefault();
              let id = $(this).data('id');
              $.get("/reports/" + id, function(data) {
                  $('#edit-report-id').val(data.id);
                  $('#edit-patient-id').val(data.patient_id).trigger('change');
                  $('#edit-report-doctor').val(data.doctor_name).trigger('change');
                  $('#edit-sample-date').val(moment(data.sample_received_on).format('YYYY-MM-DDTHH:mm'));
                  $('#edit-release-date').val(moment(data.report_released_on).format('YYYY-MM-DDTHH:mm'));
                  $('#edit-report-notes').val(data.notes || '');
                  $('#edit-report-signature-id').val(data.report_signature_id || '').trigger('change');
                  $('#edit-signature-pin').val('');
                  updateSignaturePinRequirement($('#edit-report-signature-id')[0]);
                  
                  let container = $('#edit-dynamic-tests-container');
                  container.empty();
                  
                  if(data.results && data.results.length > 0) {
                      data.results.forEach(item => {
                          let catOptions = `<option value="">-- Select Category --</option>`;
                          @foreach($categories as $cat)
                            catOptions += `<option value="{{ $cat->name }}" data-id="{{ $cat->id }}">{{ $cat->name }}</option>`;
                          @endforeach

                          let subOptions = `<option value="">-- Select Sub Category --</option>`;
                          @foreach($subCategories as $sub)
                            subOptions += `<option value="{{ $sub->name }}" data-id="{{ $sub->id }}">{{ $sub->name }}</option>`;
                          @endforeach

                           let testOptions = `<option value="">-- Select Test --</option>`;
                           @foreach($tests as $test)
                             testOptions += `<option value="{{ $test->name }}" 
                                data-unit="{{ $test->parameter->unit ?? '' }}"
                                data-male-ref="{{ $test->parameter->male_reference ?? '' }}"
                                data-female-ref="{{ $test->parameter->female_reference ?? '' }}"
                                data-male-min="{{ $test->parameter->male_min ?? '' }}"
                                data-male-max="{{ $test->parameter->male_max ?? '' }}"
                                data-female-min="{{ $test->parameter->female_min ?? '' }}"
                                data-female-max="{{ $test->parameter->female_max ?? '' }}"
                                data-critical-low="{{ $test->parameter->critical_low ?? '' }}"
                                data-critical-high="{{ $test->parameter->critical_high ?? '' }}"
                                data-reference-intervals='{{ $test->referenceIntervals->map->only(['gender', 'age_min', 'age_max', 'reference_text', 'min_value', 'max_value'])->values()->toJson() }}'
                                data-is-immunoassay="{{ $test->parameter->is_immunoassay ?? 0 }}"
                                data-bio-ref="{{ $test->parameter->biological_reference ?? '' }}"
                                data-normal="{{ $test->description }}">{{ $test->name }}</option>`;
                           @endforeach

                           let flagOptions = `<option value="">-- Select --</option>`;
                           @foreach($flagTemplates as $flg)
                             flagOptions += `<option value="{{ $flg->name }}" data-id="{{ $flg->id }}">{{ $flg->name }}</option>`;
                           @endforeach

                           let newRow = $(`
             <div class="test-item-row card border-0 shadow-sm mb-3" style="background: linear-gradient(145deg, #ffffff, #f8fafc); border-radius: 12px; position: relative; overflow: hidden;">
                 <div style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: #6366f1;"></div>
                 <div class="card-body p-3">
                     <button type="button" class="btn btn-sm btn-danger position-absolute remove-row" style="top: 10px; right: 10px; z-index: 10; border-radius: 8px;" title="Remove Test"><i class="fa fa-trash"></i></button>
                     <div class="row g-3 align-items-end">
                         <div class="col-md-4 col-sm-6">
                             <label for="field_1118"  class="form-label text-muted fs-11 fw-bold text-uppercase mb-1" style="font-size:11px;">Master Category</label>
                             <div class="input-group flex-nowrap">
                                 <select class="form-select report-category-select" name="test_category[]" autocomplete="off" id="field_1118">
                                     ${catOptions}
                                 </select>
                                 <button type="button" class="btn btn-success btn-sm btn-add-report-category" title="Add Category" style="padding: 0.25rem 0.5rem;"><i class="fa fa-plus"></i></button>
                                 <button type="button" class="btn btn-primary btn-sm btn-edit-report-category" title="Edit Category" style="padding: 0.25rem 0.5rem;"><i class="fa fa-edit"></i></button>
                                 <button type="button" class="btn btn-info btn-sm btn-view-report-category" title="View Category" style="padding: 0.25rem 0.5rem;"><i class="fa fa-eye"></i></button>
                                 <button type="button" class="btn btn-danger btn-sm btn-delete-report-category" title="Delete Category" style="padding: 0.25rem 0.5rem;"><i class="fa fa-trash"></i></button>
                             </div>
                         </div>
                         <div class="col-md-4 col-sm-6">
                             <label for="field_1119"  class="form-label text-muted fs-11 fw-bold text-uppercase mb-1" style="font-size:11px;">Sub Category</label>
                             <div class="input-group flex-nowrap">
                                 <select class="form-select report-subcategory-select" name="test_subcategory[]" autocomplete="off" id="field_1119">
                                     ${subOptions}
                                 </select>
                                 <button type="button" class="btn btn-success btn-sm btn-add-report-subcategory" title="Add Sub-Category" style="padding: 0.25rem 0.5rem;"><i class="fa fa-plus"></i></button>
                                 <button type="button" class="btn btn-primary btn-sm btn-edit-report-subcategory" title="Edit Sub-Category" style="padding: 0.25rem 0.5rem;"><i class="fa fa-edit"></i></button>
                                 <button type="button" class="btn btn-info btn-sm btn-view-report-subcategory" title="View Sub-Category" style="padding: 0.25rem 0.5rem;"><i class="fa fa-eye"></i></button>
                                 <button type="button" class="btn btn-danger btn-sm btn-delete-report-subcategory" title="Delete Sub-Category" style="padding: 0.25rem 0.5rem;"><i class="fa fa-trash"></i></button>
                             </div>
                         </div>
                         <div class="col-md-4 col-sm-6">
                             <label for="field_1003"  class="form-label text-primary fs-11 fw-bold text-uppercase mb-1" style="font-size:11px;">Parameter / Test Name</label>
                             <div class="input-group flex-nowrap">
                                 <select class="form-select test-selector-dynamic border-primary shadow-none" name="test_name[]" autocomplete="off" id="field_1003">
                                     ${testOptions}
                                 </select>
                                 <button type="button" class="btn btn-success btn-sm btn-add-report-test" title="Add Parameter" style="padding: 0.25rem 0.5rem;"><i class="fa fa-plus"></i></button>
                                 <button type="button" class="btn btn-primary btn-sm btn-edit-report-test" title="Edit Parameter" style="padding: 0.25rem 0.5rem;"><i class="fa fa-edit"></i></button>
                                 <button type="button" class="btn btn-info btn-sm btn-view-report-test" title="View Parameter Details" style="padding: 0.25rem 0.5rem;"><i class="fa fa-eye"></i></button>
                                 <button type="button" class="btn btn-danger btn-sm btn-delete-report-test" title="Delete Parameter" style="padding: 0.25rem 0.5rem;"><i class="fa fa-trash"></i></button>
                             </div>
                         </div>
                         <div class="col-md-4 col-sm-6">
                             <label for="field_1121"  class="form-label text-success fs-11 fw-bold text-uppercase mb-1" style="font-size:11px;">Observed Value</label>
                             <div class="input-group flex-nowrap">
                                 <select class="form-select report-observed-select" name="observed_value[]" autocomplete="off" id="field_1121">
                                     <option value="">-- Select Observed --</option>
                                     @foreach($templates as $tmpl)
                                         <option value="{{ $tmpl->name }}" data-id="{{ $tmpl->id }}">{{ $tmpl->name }}</option>
                                     @endforeach
                                 </select>
                                 <button type="button" class="btn btn-success btn-sm btn-add-observed" title="Add Observed" style="padding: 0.25rem 0.5rem;"><i class="fa fa-plus"></i></button>
                                 <button type="button" class="btn btn-primary btn-sm btn-edit-observed" title="Edit Observed" style="padding: 0.25rem 0.5rem;"><i class="fa fa-edit"></i></button>
                                 <button type="button" class="btn btn-info btn-sm btn-view-observed" title="View Observed Details" style="padding: 0.25rem 0.5rem;"><i class="fa fa-eye"></i></button>
                                 <button type="button" class="btn btn-danger btn-sm btn-delete-observed" title="Delete Observed" style="padding: 0.25rem 0.5rem;"><i class="fa fa-trash"></i></button>
                             </div>
                         </div>
                         <div class="col-md-4 col-sm-6">
                             <label for="field_1122"  class="form-label text-muted fs-11 fw-bold text-uppercase mb-1" style="font-size:11px;">Unit</label>
                             <div class="input-group flex-nowrap">
                                 <select class="form-select report-unit-select" name="test_unit[]" autocomplete="off" id="field_1122">
                                     <option value="">-- Select Unit --</option>
                                     @foreach($units as $u)
                                         <option value="{{ $u->name }}" data-id="{{ $u->id }}">{{ $u->name }}</option>
                                     @endforeach
                                 </select>
                                 <button type="button" class="btn btn-success btn-sm btn-add-report-unit" title="Add Unit" style="padding: 0.25rem 0.5rem;"><i class="fa fa-plus"></i></button>
                                 <button type="button" class="btn btn-primary btn-sm btn-edit-report-unit" title="Edit Unit" style="padding: 0.25rem 0.5rem;"><i class="fa fa-edit"></i></button>
                                 <button type="button" class="btn btn-info btn-sm btn-view-report-unit" title="View Unit" style="padding: 0.25rem 0.5rem;"><i class="fa fa-eye"></i></button>
                                 <button type="button" class="btn btn-danger btn-sm btn-delete-report-unit" title="Delete Unit" style="padding: 0.25rem 0.5rem;"><i class="fa fa-trash"></i></button>
                             </div>
                         </div>
                         <div class="col-md-4 col-sm-6">
                             <label for="field_1123"  class="form-label text-info fs-11 fw-bold text-uppercase mb-1" style="font-size:11px;">Referral Range</label>
                             <div class="input-group flex-nowrap">
                                 <select class="form-select normal-val-dynamic" name="normal_value[]" autocomplete="off" id="field_1123">
                                     <option value="">-- Select Reference --</option>
                                     @foreach($referenceTemplates as $ref)
                                         <option value="{{ $ref->name }}" data-id="{{ $ref->id }}">{{ $ref->name }}</option>
                                     @endforeach
                                 </select>
                                 <button type="button" class="btn btn-success btn-sm btn-add-reference" title="Add Reference" style="padding: 0.25rem 0.5rem;"><i class="fa fa-plus"></i></button>
                                 <button type="button" class="btn btn-primary btn-sm btn-edit-reference" title="Edit Reference" style="padding: 0.25rem 0.5rem;"><i class="fa fa-edit"></i></button>
                                 <button type="button" class="btn btn-info btn-sm btn-view-reference" title="View Reference" style="padding: 0.25rem 0.5rem;"><i class="fa fa-eye"></i></button>
                                 <button type="button" class="btn btn-danger btn-sm btn-delete-reference" title="Delete Reference" style="padding: 0.25rem 0.5rem;"><i class="fa fa-trash"></i></button>
                             </div>
                         </div>
                         <div class="col-md-4 col-sm-6">
                             <label for="field_1124"  class="form-label text-warning fs-11 fw-bold text-uppercase mb-1" style="font-size:11px;">Flag</label>
                             <div class="input-group flex-nowrap">
                                 <select class="form-select flag-selector" name="test_flag[]" autocomplete="off" id="field_1124">
                                     ${flagOptions}
                                 </select>
                                 <button type="button" class="btn btn-success btn-sm btn-add-flag" title="Add Flag" style="padding: 0.25rem 0.5rem;"><i class="fa fa-plus"></i></button>
                                 <button type="button" class="btn btn-primary btn-sm btn-edit-flag" title="Edit Flag" style="padding: 0.25rem 0.5rem;"><i class="fa fa-edit"></i></button>
                                 <button type="button" class="btn btn-info btn-sm btn-view-flag" title="View Flag" style="padding: 0.25rem 0.5rem;"><i class="fa fa-eye"></i></button>
                                 <button type="button" class="btn btn-danger btn-sm btn-delete-flag" title="Delete Flag" style="padding: 0.25rem 0.5rem;"><i class="fa fa-trash"></i></button>
                             </div>
                         </div>
                         <div class="col-md-8 col-sm-12">
                             <label for="field_1125"  class="form-label text-dark fs-11 fw-bold text-uppercase mb-1" style="font-size:11px;">Normal Range (Display)</label>
                             <div class="input-group flex-nowrap">
                                 <select class="form-select bio-val-dynamic" name="biological_reference[]" autocomplete="off" id="field_1125">
                                     <option value="">-- Select Range --</option>
                                     @foreach($referenceTemplates as $ref)
                                         <option value="{{ $ref->name }}" data-id="{{ $ref->id }}">{{ $ref->name }}</option>
                                     @endforeach
                                 </select>
                                 <button type="button" class="btn btn-success btn-sm btn-add-reference" title="Add Reference" style="padding: 0.25rem 0.5rem;"><i class="fa fa-plus"></i></button>
                                 <button type="button" class="btn btn-primary btn-sm btn-edit-reference" title="Edit Reference" style="padding: 0.25rem 0.5rem;"><i class="fa fa-edit"></i></button>
                                 <button type="button" class="btn btn-info btn-sm btn-view-reference" title="View Reference" style="padding: 0.25rem 0.5rem;"><i class="fa fa-eye"></i></button>
                                 <button type="button" class="btn btn-danger btn-sm btn-delete-reference" title="Delete Reference" style="padding: 0.25rem 0.5rem;"><i class="fa fa-trash"></i></button>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
                            `);
                           container.append(newRow);
                           setSelectValueWithDefault(newRow.find('.report-category-select'), item.category);
                           setSelectValueWithDefault(newRow.find('.report-subcategory-select'), item.subcategory);
                           setSelectValueWithDefault(newRow.find('.test-selector-dynamic'), item.name);
                           setSelectValueWithDefault(newRow.find('.report-observed-select'), item.observed_value);
                           setSelectValueWithDefault(newRow.find('.report-unit-select'), item.unit);
                           setSelectValueWithDefault(newRow.find('.flag-selector'), item.flag);
                           setSelectValueWithDefault(newRow.find('.normal-val-dynamic'), item.normal_value);
                           setSelectValueWithDefault(newRow.find('.bio-val-dynamic'), item.biological_reference);
                      });
                      initDynamicSelect2();
                  } else {
                      container.append(trTemplate);
                      initDynamicSelect2();
                  }
              });
          });

          // Update Report
          $('#btn-update-report').click(function() {
              if(!$('#form-edit-report')[0].checkValidity()) {
                  $('#form-edit-report')[0].reportValidity();
                  return;
              }

			  let btn = $(this);
              let id = $('#edit-report-id').val();
              btn.html('<i class="fa fa-spinner fa-spin"></i> Updating...').prop('disabled', true);

			  
			  $.ajax({
                  url: "/reports/" + id,
                  type: 'PUT',
                  data: $('#form-edit-report').serialize(),
                  success: function(response) {
                      alert(response.success);
                      refreshReportsPageData('#modal-edit-report', btn, 'Update Report');
                  },
                  error: function(xhr) {
                      alert(xhr.responseJSON.message || "Error updating report.");
                      btn.html('Update Report').prop('disabled', false);
                  }
              });
		  });

		  // View Report (Chrome-like Viewer Logic)
          let currentZoom = 1;
          const ZOOM_STEP = 0.1;
          const MAX_ZOOM = 2.0;
          const MIN_ZOOM = 0.25;

          // â”€â”€ Header Toggle â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
          let showReportHeader = true;

          $(document).on('click', '.pdf-header-toggle', function() {
              showReportHeader = $(this).attr('id') === 'btn-with-header';
              $('.pdf-header-toggle').removeClass('is-active');
              $(this).addClass('is-active');

              // Update Dropdown UI
              if (showReportHeader) {
                  $('#current-mode-text').text('With Header');
                  $('#current-mode-icon').removeClass('fa-file-o').addClass('fa-id-card-o');
              } else {
                  $('#current-mode-text').text('No Header');
                  $('#current-mode-icon').removeClass('fa-id-card-o').addClass('fa-file-o');
              }

              let id = $('#btn-viewer-download').data('current-id');
              if (!id) return;
              $('#pdf-canvas-container').html(`<div class="text-center py-100" style="color:#64748b;"><div style="width:40px;height:40px;border:3px solid rgba(99,102,241,0.3);border-top-color:#6366f1;border-radius:50%;animation:spin 0.9s linear infinite;margin:0 auto 16px;"></div><p style="font-size:13px;">Re-generating...</p></div>`);
              $.get("/reports/" + id, function(data) {
                  const doc = createPDFDocument(data, showReportHeader);
                  const pdfBlob = doc.output('blob');
                  renderPDF(URL.createObjectURL(pdfBlob));
              }).fail(function(xhr) {
                  $('#pdf-canvas-container').html(`<div class="alert alert-danger m-20">Could not reload report preview. ${xhr.responseJSON?.message || 'Please try again.'}</div>`);
              });
          });
          // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

		  $(document).on('click', '.btn-view', function(e) {
			  e.preventDefault();
			  let id = $(this).data('id');

              // Open modal
              $('#modal-view-report').modal('show');

              // Reset header toggle to 'With Header'
              showReportHeader = true;
              $('.pdf-header-toggle').removeClass('is-active');
              $('#btn-with-header').addClass('is-active');
              
              $('#btn-viewer-download').data('current-id', id);
              $('#btn-viewer-print').data('current-id', id);
              $('#btn-viewer-share').data('current-id', id);
              
			  $('#pdf-canvas-container').html(`
				<div class="text-center py-100">
					<div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
						<span class="visually-hidden">Loading...</span>
					</div>
					<p class="mt-20 text-muted fs-16">Generating real PDF preview...</p>
				</div>
			  `);
              
              // Reset zoom
              currentZoom = 1;
              applyZoom();
			  
			  $.get("/reports/" + id, function(data) {
                  let p = data.patient;
                  let filename = `Report_${p.first_name}_${id}.pdf`;
                  $('#viewer-filename-link').text(filename);
                  $('#viewer-filename-link').attr('title', `Click to open ${filename} directly`);

                  // Generate PDF Blob
                  const doc = createPDFDocument(data, showReportHeader);
                  const pdfBlob = doc.output('blob');
                  const pdfUrl = URL.createObjectURL(pdfBlob);

                  // Render PDF using PDF.js
                  renderPDF(pdfUrl).then(() => {
                      // On mobile, default to fit width
                      if (window.innerWidth < 768) {
                          $('#fit-width').click();
                      }
                  });
			  }).fail(function(xhr) {
                  $('#pdf-canvas-container').html(`<div class="alert alert-danger m-20">Could not load report preview. ${xhr.responseJSON?.message || 'Please try again.'}</div>`);
              });
		  });

          async function renderPDF(url) {
              try {
                  if (!window.pdfjsLib) {
                      throw new Error('PDF preview engine did not load.');
                  }

                  const loadingTask = pdfjsLib.getDocument(url);
                  const pdf = await loadingTask.promise;
                  const container = document.getElementById('pdf-canvas-container');
                  container.innerHTML = ''; // Clear loader

                  for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                      const page = await pdf.getPage(pageNum);
                      const viewport = page.getViewport({ scale: 1.5 }); // High resolution render
                      
                      const wrapper = document.createElement('div');
                      wrapper.className = 'pdf-canvas-wrapper mb-20';
                      
                      const canvas = document.createElement('canvas');
                      const context = canvas.getContext('2d');
                      canvas.height = viewport.height;
                      canvas.width = viewport.width;
                      canvas.dataset.baseWidth = viewport.width;
                      canvas.style.width = `${viewport.width * currentZoom}px`;

                      const renderContext = {
                          canvasContext: context,
                          viewport: viewport
                      };
                      
                      wrapper.appendChild(canvas);
                      container.appendChild(wrapper);
                      
                      await page.render(renderContext).promise;
                  }

                  applyZoom();
              } catch (error) {
                  console.error('PDF Rendering Error:', error);
                  $('#pdf-canvas-container').html(`<div class="alert alert-danger m-20">Error rendering PDF: ${error.message}</div>`);
              }
          }

          // Zoom Functions
          function applyZoom() {
              $('#pdf-page-container').css('transform', 'none');
              $('#pdf-canvas-container canvas').each(function() {
                  const baseWidth = parseFloat(this.dataset.baseWidth || this.width || 794);
                  $(this).css('width', `${baseWidth * currentZoom}px`);
              });
              $('#zoom-text').text(`${Math.round(currentZoom * 100)}%`);
          }

          $('#zoom-in').click(function() {
              if (currentZoom < MAX_ZOOM) {
                  currentZoom += ZOOM_STEP;
                  applyZoom();
              }
          });

          $('#zoom-out').click(function() {
              if (currentZoom > MIN_ZOOM) {
                  currentZoom -= ZOOM_STEP;
                  applyZoom();
              }
          });

          $('#fit-width').click(function() {
              let containerWidth = $('#pdf-viewport').width() - (window.innerWidth < 768 ? 14 : 80);
              let firstCanvas = $('#pdf-canvas-container canvas').first()[0];
              let pageWidth = parseFloat(firstCanvas?.dataset.baseWidth || firstCanvas?.width || 794);
              currentZoom = containerWidth / pageWidth;
              if (currentZoom > MAX_ZOOM) currentZoom = MAX_ZOOM;
              if (currentZoom < MIN_ZOOM) currentZoom = MIN_ZOOM;
              applyZoom();
          });

          $('#fit-page').click(function() {
              let containerHeight = $('#pdf-viewport').height() - 80;
              let pageHeight = 1123; // A4 Height in px roughly
              currentZoom = containerHeight / pageHeight;
              if (currentZoom > MAX_ZOOM) currentZoom = MAX_ZOOM;
              if (currentZoom < MIN_ZOOM) currentZoom = MIN_ZOOM;
              applyZoom();
          });

          // Keyboard Navigation
          $(document).on('keydown', function(e) {
              // Only active when PDF viewer is open
              if (!$('#modal-view-report').hasClass('show')) return;
              
              const viewport = $('#pdf-viewport')[0];
              const scrollStep = 100;
              const pageStep = $('#pdf-viewport').height() * 0.85;
              
              switch(e.key) {
                  case 'ArrowDown':
                      viewport.scrollTop += scrollStep;
                      e.preventDefault();
                      break;
                  case 'ArrowUp':
                      viewport.scrollTop -= scrollStep;
                      e.preventDefault();
                      break;
                  case 'PageDown':
                  case ' ': // Space bar
                      if (!$(e.target).is('input, textarea, select')) {
                          $('#pdf-viewport').stop().animate({ scrollTop: $('#pdf-viewport').scrollTop() + pageStep }, 200);
                          e.preventDefault();
                      }
                      break;
                  case 'PageUp':
                      $('#pdf-viewport').stop().animate({ scrollTop: $('#pdf-viewport').scrollTop() - pageStep }, 200);
                      e.preventDefault();
                      break;
                  case 'Home':
                      $('#pdf-viewport').stop().animate({ scrollTop: 0 }, 300);
                      e.preventDefault();
                      break;
                  case 'End':
                      $('#pdf-viewport').stop().animate({ scrollTop: viewport.scrollHeight }, 300);
                      e.preventDefault();
                      break;
                  case '+':
                  case '=':
                      if (e.ctrlKey) { $('#zoom-in').click(); e.preventDefault(); }
                      break;
                  case '-':
                  case '_':
                      if (e.ctrlKey) { $('#zoom-out').click(); e.preventDefault(); }
                      break;
                  case '0':
                      if (e.ctrlKey) { currentZoom = 1; applyZoom(); e.preventDefault(); }
                      break;
              }
          });

          $('#btn-viewer-fullscreen').click(function() {
              const elem = document.querySelector('.pdf-viewer-wrapper');
              if (!document.fullscreenElement) {
                  elem.requestFullscreen().catch(err => {
                      alert(`Error attempting to enable full-screen mode: ${err.message}`);
                  });
                  $(this).find('i').removeClass('fa-expand').addClass('fa-compress');
              } else {
                  document.exitFullscreen();
                  $(this).find('i').removeClass('fa-compress').addClass('fa-expand');
              }
          });

          $('#btn-viewer-print').click(function() {
              let id = $(this).data('current-id');
              $.get("/reports/" + id, function(data) {
                  const doc = createPDFDocument(data, showReportHeader);
                  window.open(doc.output('bloburl'), '_blank');
              });
          });

          $(document).on('click', '#viewer-filename-link', function() {
              let id = $('#btn-viewer-download').data('current-id');
              generateAndOpenPDF(id);
          });

          function generateAndOpenPDF(reportId) {
              const { jsPDF } = window.jspdf;
              $.get("/reports/" + reportId, function(data) {
                  const doc = createPDFDocument(data, showReportHeader);
                  window.open(doc.output('bloburl'), '_blank');
              });
          }

          // Helper to create the Awwal-style lab report PDF.
          function createPDFDocument(data, showHeader = true) {
              const { jsPDF } = window.jspdf;
              const doc = new jsPDF();
              const p = data.patient;
              const referenceNo = (p.patient_id || '').replace('#P-', '').replace('#', '');
              const patientName = `${(p.first_name || '').toUpperCase()} ${(p.last_name || '').toUpperCase()}`.trim();
              const sex = (p.gender || '').toUpperCase();
              const reportDate = moment(data.sample_received_on).format('DD-MMM-YYYY - hh:mm:ss A');
              const printedDate = moment().format('DD-MMM-YYYY - hh:mm:ss A');
              const reportNotes = (data.notes || '').trim();
              const signature = data.signature || null;

              const pageW = doc.internal.pageSize.getWidth();
              const pageH = doc.internal.pageSize.getHeight();
              const left = 21;
              const tableW = 173;
	              const col1 = 61;
	              const col2 = 44;
	              const col4 = 14;
	              const col3 = tableW - col1 - col2 - col4;
              const footerTop = 269;
              let pageNo = 1;

              function addShell(isFirstPage = true) {
                  if (showHeader) {
                      const hProps = doc.getImageProperties(REPORT_HEADER_IMAGE);
                      const hHeight = (hProps.height * pageW) / hProps.width;
                      doc.addImage(REPORT_HEADER_IMAGE, 'PNG', 0, 0, pageW, hHeight, undefined, 'FAST');
                  }

                  const fProps = doc.getImageProperties(REPORT_FOOTER_IMAGE);
                  const fHeight = (fProps.height * pageW) / fProps.width;
                  const actualFooterTop = pageH - fHeight;
                  doc.addImage(REPORT_FOOTER_IMAGE, 'PNG', 0, actualFooterTop, pageW, fHeight, undefined, 'FAST');
                  doc.setTextColor(0);
                  doc.setDrawColor(0);
                  doc.setLineWidth(0.25);

                  const infoY = isFirstPage ? 44 : 44;
                  doc.setFont('times', 'normal');
                  doc.setFontSize(11);

                  doc.text(isFirstPage ? 'Patient Name' : 'Name', left + 2, infoY);
                  doc.text(':', left + 28, infoY);
                  doc.setFont('times', 'bold');
                  doc.text(patientName, left + 31, infoY);

                  doc.setFont('times', 'normal');
                  doc.text('Age', 132, infoY);
                  doc.text(':', 158, infoY);
                  doc.text(`${p.age || ''} ${p.age_type || 'Years'}`, 160, infoY);
                  doc.text(`Sex : ${sex}`, 178, infoY);

                  doc.text('Specimen', 132, infoY + 5);
                  doc.text(':', 158, infoY + 5);

                  doc.text('Reference No', left + 2, infoY + 10);
                  doc.text(':', left + 28, infoY + 10);
                  doc.text(referenceNo, left + 31, infoY + 10);

                  doc.text('Date', 132, infoY + 10);
                  doc.text(':', 158, infoY + 10);
                  doc.text(isFirstPage ? reportDate : moment(data.sample_received_on).format('DD-MMM-YYYY'), 160, infoY + 10);

                  doc.text('Referred By', left + 2, infoY + 15);
                  doc.text(':', left + 28, infoY + 15);
                  doc.setFont('times', 'bold');
                  doc.text(data.doctor_name || '', left + 31, infoY + 15);
                  doc.setFont('times', 'normal');

                  if (isFirstPage) {
                      doc.text('Printed Date', 132, infoY + 15);
                      doc.text(':', 158, infoY + 15);
                      doc.text(printedDate, 160, infoY + 15);
                  }

                  doc.line(0, infoY + 18, pageW, infoY + 18);
              }

              function addNewPage() {
                  doc.addPage();
                  pageNo += 1;
                  addShell(false);
                  return 70;
              }

              function drawCategoryTitle(title, y) {
                  doc.setFont('times', 'bold');
                  doc.setFontSize(13);
                  doc.text((title || 'GENERAL').toUpperCase(), pageW / 2, y, { align: 'center' });
                  return y + 9;
              }

              function drawTableHeader(y) {
                  doc.setFont('times', 'bold');
                  doc.setFontSize(11);
	                  doc.rect(left, y, tableW, 8);
	                  doc.line(left + col1, y, left + col1, y + 8);
	                  doc.line(left + col1 + col2, y, left + col1 + col2, y + 8);
	                  doc.line(left + col1 + col2 + col3, y, left + col1 + col2 + col3, y + 8);
	                  doc.text('Parameter', left + 5, y + 5.5);
	                  doc.text('Observed Value', left + col1 + col2 / 2, y + 5.5, { align: 'center' });
	                  doc.text('Reference Value', left + col1 + col2 + col3 / 2, y + 5.5, { align: 'center' });
	                  doc.text('Flag', left + col1 + col2 + col3 + col4 / 2, y + 5.5, { align: 'center' });
	                  return y + 8;
	              }

	              function drawCellRow(y, name, observed, reference, flag = '', boldFirst = false) {
	                  doc.setFontSize(11);
	                  const nameLines = doc.splitTextToSize(name || '', col1 - 4);
	                  const observedLines = doc.splitTextToSize(observed || '', col2 - 4);
	                  const refLines = doc.splitTextToSize(reference || '', col3 - 4);
                  const lineCount = Math.max(nameLines.length, observedLines.length, refLines.length, 1);
                  const rowH = Math.max(6, lineCount * 5.2);

                  if (y + rowH > footerTop - 12) {
                      y = addNewPage();
                      y = drawTableHeader(y);
                  }

	                  doc.rect(left, y, tableW, rowH);
	                  doc.line(left + col1, y, left + col1, y + rowH);
	                  doc.line(left + col1 + col2, y, left + col1 + col2, y + rowH);
	                  doc.line(left + col1 + col2 + col3, y, left + col1 + col2 + col3, y + rowH);

                  doc.setFont('times', boldFirst ? 'bold' : 'normal');
                  doc.text(nameLines, left + 2, y + 4.5);
                  doc.setFont('times', 'bold');
                  doc.text(observedLines, left + col1 + 2, y + 4.5);
	                  doc.setFont('times', 'normal');
	                  doc.text(refLines, left + col1 + col2 + 2, y + 4.5);
	                  doc.setFont('times', 'bold');
	                  if (flag === 'C') doc.setTextColor(208, 0, 0);
	                  doc.text(flag || '', left + col1 + col2 + col3 + col4 / 2, y + 4.5, { align: 'center' });
	                  doc.setTextColor(0);
	                  return y + rowH;
	              }

              function imageFormatFromDataUrl(dataUrl) {
                  if (!dataUrl || dataUrl.indexOf('image/jpeg') !== -1 || dataUrl.indexOf('image/jpg') !== -1) {
                      return 'JPEG';
                  }

                  return 'PNG';
              }

              addShell(true);

              let groupedResults = {};
              (data.results || []).forEach(r => {
                  const cat = (r.category || 'GENERAL').toUpperCase();
                  if (!groupedResults[cat]) groupedResults[cat] = [];
                  groupedResults[cat].push(r);
              });

              let y = 72;
              Object.keys(groupedResults).forEach(cat => {
                  if (y > footerTop - 35) y = addNewPage();
                  y = drawCategoryTitle(cat, y);
                  y = drawTableHeader(y);

                  let lastSubheading = null;
                  groupedResults[cat].forEach(r => {
	                      const subheading = (r.subcategory || '').trim();
	                      if (subheading && subheading !== lastSubheading) {
	                          y = drawCellRow(y, subheading.toUpperCase(), '', '', '', true);
	                          lastSubheading = subheading;
	                      }

	                      const observed = `${r.observed_value || ''}${r.unit ? '  ' + r.unit : ''}`.trim();
	                      const reference = r.normal_value || r.biological_reference || '';
	                      y = drawCellRow(y, r.name || '', observed, reference, r.flag || '');
	                  });

                  y += 8;
              });

              if (y > footerTop - 55) y = addNewPage();
              doc.setFont('times', 'normal');
              doc.setFontSize(11);
              doc.text('Note :', left, y + 5);

              if (reportNotes) {
                  const noteLines = doc.splitTextToSize(reportNotes, 116);
                  if (y + 8 + noteLines.length * 5 > footerTop - 18) {
                      y = addNewPage();
                      doc.text('Note :', left, y + 5);
                  }
                  doc.text(noteLines, left + 13, y + 5);
              }

              if (signature && signature.image_data) {
                  try {
                      doc.addImage(signature.image_data, imageFormatFromDataUrl(signature.image_data), 154, footerTop - 34, 38, 18, undefined, 'FAST');
                  } catch (error) {
                      console.warn('Could not add signature image:', error);
                  }
              }

              doc.setFont('times', 'bold');
              doc.text(signature?.name || 'Medi Technician', 174, footerTop - 8, { align: 'center' });
              doc.setFont('times', 'normal');
              if (pageNo > 1) {
                  doc.setFontSize(9);
                  doc.text(`Page No :${pageNo}`, 12, 36);
              }

              return doc;
          }

          $('#btn-viewer-download').click(function() {
              let id = $(this).data('current-id');
              generateAndDownloadPDF(id, showReportHeader);
          });

          // Share Report PDF
          $('#btn-viewer-share').click(function() {
              let id = $(this).data('current-id');
              let btn = $(this);
              let originalHtml = btn.html();
              btn.html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);

              $.get("/reports/" + id, function(data) {
                  const doc = createPDFDocument(data, showReportHeader);
                  const p = data.patient;
                  const filename = `Report_${p.first_name}_${p.last_name}_${id}.pdf`;

                  // Generate PDF as Blob
                  const pdfBlob = doc.output('blob');
                  const pdfFile = new File([pdfBlob], filename, { type: 'application/pdf' });

                  // Try Web Share API (supports file sharing on mobile)
                  if (navigator.share && navigator.canShare && navigator.canShare({ files: [pdfFile] })) {
                      navigator.share({
                          title: `Lab Report - ${p.first_name} ${p.last_name}`,
                          text: `Lab Report for ${p.first_name} ${p.last_name}. Please find the attached PDF report.`,
                          files: [pdfFile]
                      }).then(() => {
                          console.log('Report shared successfully.');
                      }).catch(err => {
                          if (err.name !== 'AbortError') {
                              console.warn('Share failed, falling back:', err);
                              shareViaWhatsApp(p, filename, pdfBlob);
                          }
                      });
                  } else if (navigator.share) {
                      // Share API available but no file support â€” share link/text
                      navigator.share({
                          title: `Lab Report - ${p.first_name} ${p.last_name}`,
                          text: `Lab Report for ${p.first_name} ${p.last_name} (ID: ${p.patient_id}). Generated from SUHAIM SOFT LAB Management System.`,
                      }).catch(err => console.warn('Share failed:', err));
                  } else {
                      // Desktop fallback: WhatsApp + auto-download
                      shareViaWhatsApp(p, filename, pdfBlob);
                  }

                  btn.html(originalHtml).prop('disabled', false);
              }).fail(function() {
                  alert('Failed to generate report for sharing.');
                  btn.html(originalHtml).prop('disabled', false);
              });
          });

          function shareViaWhatsApp(patient, filename, pdfBlob) {
              // Auto-download the PDF first
              const url = URL.createObjectURL(pdfBlob);
              const a = document.createElement('a');
              a.href = url;
              a.download = filename;
              a.click();
              URL.revokeObjectURL(url);

              // Open WhatsApp with a pre-filled message
              const phone = patient.phone ? patient.phone.replace(/\D/g, '') : '';
              const msg = encodeURIComponent(`Lab Report for ${patient.first_name} ${patient.last_name} (ID: ${patient.patient_id}).\n\nPlease find your report PDF that has been downloaded. You can attach it manually.`);
              const waUrl = phone
                  ? `https://wa.me/${phone}?text=${msg}`
                  : `https://wa.me/?text=${msg}`;

              setTimeout(() => window.open(waUrl, '_blank'), 500);
          }

          function generateAndDownloadPDF(reportId, withHeader = true, callback) {
              $.get("/reports/" + reportId, function(data) {
                  const doc = createPDFDocument(data, withHeader);
                  const p = data.patient;
                  doc.save(`Report_${p.first_name}_${reportId}.pdf`);
                  if (callback) callback();
              });
          }

		  // Delete Report
		  $(document).on('click', '.btn-delete', function(e) {
			  e.preventDefault();
              let id = $(this).data('id');
              if(confirm('Are you sure you want to delete this report?')) {
                  $.ajax({
                      url: "/reports/" + id,
                      type: 'DELETE',
                      success: function(response) {
                          alert(response.success);
                          refreshReportsPageData();
                      }
                  });
              }
		  });

	  });
  