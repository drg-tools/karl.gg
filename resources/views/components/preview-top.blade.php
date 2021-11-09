<div>
    <div class="previewHeaderBackground text-gray-300">
        <div class="flex justify-between items-center">
            <h2 class="p-4">by <a class="text-karl-orange" href="/profile/" . {{$creatorId}}>{{$creatorName}}</a> on
                {{$updatedAt}}
            </h2>
            {{-- <div class="flex">
                <div class="button" v-on:click="onEditClick">
                    <span class="buttonText" v-if="userOwnsLoadout">EDIT</span>
                    <span class="buttonText" v-else>COPY</span>
                </div>
                <input type="hidden" v-model="this.pageUrl"/>
                <div class="button"
                     v-clipboard:copy="pageUrl"
                     v-clipboard:success="onCopy"
                     v-clipboard:error="onError">
                    <h1 class="buttonText">SHARE</h1>
                </div>
            </div> --}}
            <!-- todo: use texts from old karl and style toast messages -->
        </div>
        {{-- <div class="previewHeaderContainer p-4" :class="getHeaderImageClass">
            <div class="previewFooter mt-4">
                <!-- todo: tooltip on salutes container! -->
                <div v-on:click="onToggleVote" class="bg-gray-900 font-bold sm:rounded text-center p-2 cursor-pointer hover:bg-gray-800">
                    <span>Salutes</span>
                    <img src="/assets/img/bosco.png" :class="getUserVotedState" class="bosco-salute"/>
                    <span class="salute-count">{{ loadoutDetails.votes }}</span>
                </div>
            </div>
        </div> --}}

       
    </div>
</div>