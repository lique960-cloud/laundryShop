<!-- Customer Detail Modal -->
<div class="modal fade" id="modal-customer-detail" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-user" style="margin-right:8px; color:#818cf8;"></i>Customer Details</h4>
      </div>
      <div class="modal-body">
        <div style="display:flex; flex-direction:column; gap:16px;">
          <div style="display:flex; align-items:center; gap:14px; padding-bottom:16px; border-bottom:1px solid rgba(148,163,184,0.1);">
            <div style="width:52px; height:52px; border-radius:14px; background:linear-gradient(135deg, #6366f1, #8b5cf6); display:flex; align-items:center; justify-content:center; font-size:22px; color:#fff; flex-shrink:0;">👤</div>
            <div>
              <div id="detail-name" style="font-size:18px; font-weight:700; color:#f1f5f9;"></div>
              <div id="detail-date" style="font-size:12px; color:#64748b; margin-top:2px;"></div>
            </div>
          </div>

          <div class="detail-row">
            <div class="detail-label"><i class="fa fa-envelope" style="margin-right:8px; color:#0ea5e9;"></i>Email Address</div>
            <div class="detail-value" id="detail-email"></div>
          </div>

          <div class="detail-row">
            <div class="detail-label"><i class="fa fa-phone" style="margin-right:8px; color:#818cf8;"></i>Contact Number</div>
            <div class="detail-value" id="detail-mobile"></div>
          </div>

          <div class="detail-row">
            <div class="detail-label"><i class="fa fa-map-marker" style="margin-right:8px; color:#818cf8;"></i>Pickup & Delivery Address</div>
            <div class="detail-value" id="detail-address" style="line-height:1.5;"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Customer Delete Confirm Modal -->
<div class="modal fade" id="modal-customer-delete" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-exclamation-triangle" style="margin-right:8px; color:#818cf8;"></i>Confirm Delete</h4>
      </div>
      <div class="modal-body">
        <p style="color:#94a3b8; font-size:14px;">Are you sure you want to delete customer <strong id="delete-customer-name" style="color:#f1f5f9;"></strong>?</p>
        <p style="color:#64748b; font-size:12.5px; margin-top:8px;">This action cannot be undone.</p>
        <input type="hidden" id="delete-customer-id" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirm-delete-customer"><i class="fa fa-trash" style="margin-right:5px;"></i>Delete</button>
      </div>
    </div>
  </div>
</div>

<style>
  .detail-row {
    padding: 10px 0;
    border-bottom: 1px solid rgba(148,163,184,0.06);
  }
  .detail-row:last-child {
    border-bottom: none;
  }
  .detail-label {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
  }
  .detail-value {
    font-size: 14px;
    color: #e2e8f0;
    font-weight: 500;
  }
</style>
