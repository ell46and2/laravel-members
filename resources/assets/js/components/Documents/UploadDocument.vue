<template>
  <div>
    <div class="panel">
        <div class="panel__inner">
            <div class="panel__header">
                <h2 class="panel__heading">
                    Add a Document
                    <div class="[ text--color-base text--size-base ] [ font-weight-normal ] [ mt-1 ]">Add a Document for all other users to access</div>
                </h2>
            </div>

            <div class="panel__main flow-vertical--3">

                <div class="mt-2 form-group flow-vertical--1">
                    <label class="text--color-blue">Document Name</label>
                    <input class="form-control" type="text" name="title" value="" placeholder="Enter name...">
                </div>

                <div class="mt-2 form-group flow-vertical--1">
                    <label class="text--color-blue">Description</label>
                    <input class="form-control" type="text" name="description" value="" placeholder="Enter description...">
                </div>

                <div class="flow-vertical--1">
                    <label class="text--color-blue">Upload Document</label>
                    <button class="button button--primary button--block" type="button">Attach a File</button>
                </div>
            </div>

        </div>
    </div>

    <button class="button button--primary button--block" type="button">Upload</button>
  </div>

</template>


<script>
  
  export default {

  }

</script>