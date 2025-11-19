<?php /* NAVBAR */ ?>

<nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all ease-in shadow-none duration-250 rounded-2xl fixed top-0 left-0 w-full z-50">
    <div class="flex items-center justify-between w-full px-4 py-1 mx-auto">

        <!-- Breadcrumb -->
        <nav>
            <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
                <li class="text-sm leading-normal">
                    <a class="text-white opacity-50" href="javascript:;">Pages</a>
                </li>
                <li class="text-sm pl-2 capitalize leading-normal text-white before:content-['/']"
                    aria-current="page">
                    <?= $page_title ?? "Dashboard" ?>
                </li>
            </ol>
            <h6 class="mb-0 font-bold text-white capitalize"><?= $page_title ?? "Dashboard" ?></h6>
        </nav>

        <!-- RIGHT SIDE ITEMS (search, icons) -->
        <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0">

            <!-- Search -->
            <div class="flex items-center md:ml-auto md:pr-4">
                <div class="relative flex">
                    <input type="text"
                           class="pl-9 text-sm bg-white border border-gray-300 rounded-lg py-2 pr-3"
                           placeholder="Type here..." />
                </div>
            </div>

            <!-- Icons -->
            <ul class="flex flex-row justify-end pl-0 mb-0 list-none">
                <li class="flex items-center">
                    <a href="#" class="px-0 py-2 text-sm font-semibold text-white">
                        <i class="fa fa-user mr-1"></i>
                        Sign In
                    </a>
                </li>

                <li class="flex items-center px-4">
                    <a href="#" class="p-0 text-sm text-white">
                        <i class="cursor-pointer fa fa-cog"></i>
                    </a>
                </li>
            </ul>

        </div>
    </div>
</nav>
