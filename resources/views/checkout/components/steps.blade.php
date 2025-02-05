<div class="flex justify-between mb-6">
    <template x-for="num in [1,2,3,4]" :key="num">
        <div class="flex flex-col items-center">
            <div class="w-5 h-5 flex items-center justify-center rounded-full text-white font-bold"
                :class="step === num ? 'bg-blue-500' : 'bg-gray-300'">
                <span x-text="num"></span>
            </div>
        </div>
    </template>
</div>
