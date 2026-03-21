<div class="modal fade" id="newMessageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-pen-fancy text-primary me-2"></i>
                    New Message
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="newMessageForm">
                    <div class="mb-3">
                        <label class="form-label">Select Recipient</label>
                        <select class="form-select" id="recipientSelect" required>
                            <option value="">Choose a user...</option>
                            @foreach($users ?? [] as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" rows="4" id="newMessageContent" 
                                  placeholder="Write your message..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="Messages.sendNewMessage()">
                    <i class="fas fa-paper-plane me-2"></i>
                    Send Message
                </button>
            </div>
        </div>
    </div>
</div>