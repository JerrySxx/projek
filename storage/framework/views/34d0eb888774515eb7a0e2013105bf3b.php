
<div class="flex gap-3 py-4 border-b border-modern/50 hover-modern transition-all pl-2">
    <img src="<?php echo e($comment->user->profile_photo_url); ?>" class="w-9 h-9 rounded-full object-cover shrink-0 ring-1 ring-white/10" alt="">
    <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2 text-sm">
            <span class="font-bold text-gray-200"><?php echo e($comment->user->name); ?></span>
            <span class="text-gray-600"> · <?php echo e($comment->created_at->diffForHumans()); ?></span>
        </div>
        <p class="text-[15px] mt-1 leading-relaxed text-gray-300"><?php echo linkHashtags($comment->content); ?></p>
        
        <?php if($comment->image_path): ?>
            <img src="<?php echo e(Storage::url($comment->image_path)); ?>" class="mt-3 rounded-xl border border-modern w-full object-cover max-h-[250px] ring-1 ring-white/5" alt="">
        <?php endif; ?>
        <?php if($comment->file_path): ?>
            <a href="<?php echo e(Storage::url($comment->file_path)); ?>" download="<?php echo e($comment->file_name); ?>"
               class="inline-flex items-center gap-1.5 mt-2 px-2.5 py-1.5 bg-white/5 border border-modern rounded-lg text-blue-400 text-xs hover:bg-white/10 transition-colors">
                <i data-lucide="file-text" class="w-3 h-3"></i> <?php echo e($comment->file_name); ?>

            </a>
        <?php endif; ?>
        

        <div class="flex items-center gap-4 mt-2 mb-3">
            
            <button type="button" onclick="event.stopPropagation(); toggleCommentLike(this, <?php echo e($comment->id); ?>)" 
                    class="flex items-center gap-1.5 text-sm transition-colors p-1.5 rounded-full hover:bg-pink-500/10 <?php echo e($comment->isLikedByAuthUser() ? 'text-pink-500' : 'text-gray-600 hover:text-pink-400'); ?>">
                <i data-lucide="heart" class="w-[16px] h-[16px]"></i>
                <span class="like-count text-xs"><?php echo e($comment->likes->count()); ?></span>
            </button>

            
            <button type="button" onclick="toggleReplyForm(<?php echo e($comment->id); ?>)" 
                    class="text-xs text-gray-600 hover:text-blue-400 font-medium transition-colors flex items-center gap-1">
                <i data-lucide="reply" class="w-3 h-3"></i>
                Balas
            </button>

            <?php if($comment->children->count() > 0): ?>
                <span class="text-xs text-gray-600"><?php echo e($comment->children->count()); ?> balasan</span>
            <?php endif; ?>

            <?php if($comment->user_id === Auth::id()): ?>
                <a href="<?php echo e(route('comments.edit', $comment->id)); ?>" class="text-xs text-gray-600 hover:text-blue-400 font-medium transition-colors flex items-center gap-1">
                    <i data-lucide="pencil" class="w-3 h-3"></i> Edit
                </a>
                <form method="POST" action="<?php echo e(route('comments.destroy', $comment->id)); ?>" class="inline" onsubmit="event.stopPropagation(); return confirm('Hapus komentar ini?');">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="text-xs text-gray-600 hover:text-red-400 p-1 rounded hover:bg-red-500/10 transition-colors">
                        <i data-lucide="trash-2" class="w-3 h-3"></i>
                    </button>
                </form>
            <?php endif; ?>
        </div>

        
        <div id="reply-form-<?php echo e($comment->id); ?>" class="reply-form hidden pl-10 border-l-2 border-blue-500/30 bg-blue-500/5 rounded py-2 mb-2">
            <form method="POST" action="<?php echo e(route('comments.reply', [$comment->post_id, $comment->id])); ?>" enctype="multipart/form-data" class="flex gap-2">
                <?php echo csrf_field(); ?>
                <img src="<?php echo e(Auth::user()->profile_photo_url); ?>" class="w-7 h-7 rounded-full object-cover shrink-0 ring-1 ring-white/10 mt-1" alt="">
                <div class="flex-1">
                    <textarea name="content" maxlength="250" required placeholder="Tulis balasan..." 
                        class="w-full bg-transparent text-[15px] resize-none outline-none placeholder-gray-600 min-h-[40px] p-2 rounded border border-modern/50 focus:border-blue-400"
                        oninput="document.getElementById('rCharCount-<?php echo e($comment->id); ?>').textContent=250-this.value.length"></textarea>
                    <div class="flex justify-between items-center pt-1 mt-1">
                        <span id="rCharCount-<?php echo e($comment->id); ?>" class="text-xs text-gray-600">250</span>
                        <button type="submit" class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded font-medium transition-colors">Kirim</button>
                    </div>
                </div>
            </form>
        </div>

        
        <?php $__currentLoopData = $comment->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('partials.comment-card', ['comment' => $child], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<script>
function toggleReplyForm(commentId) {
    const form = document.getElementById('reply-form-' + commentId);
    form.classList.toggle('hidden');
}
</script>
<?php /**PATH C:\laragon\www\violet\resources\views/partials/comment-card.blade.php ENDPATH**/ ?>