<!-- Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel">Contact Us</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="contactForm" action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="contactEmail" class="form-label">Your Email</label>
                        <input type="email" class="form-control" id="contactEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="contactSubject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="contactSubject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="contactMessage" class="form-label">Message</label>
                        <textarea class="form-control" id="contactMessage" name="message" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</div>
