<nav class="navbar navbar-default">
    <div class="container-fluid">

        <div class="navbar-header">
            <button type="button"
                    class="navbar-toggle collapsed"
                    data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1"
                    aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('staff.dashboard') }}">
                <img alt="Ching Shop"
                     src="/img/logo-plain.svg"
                     class="img-responsive navbar-logo">
            </a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="dropdown
                    {{ $location->putActive('staff.products') }}">
                    <a href="#" class="dropdown-toggle"
                       data-toggle="dropdown"
                       role="button"
                       aria-haspopup="true"
                       aria-expanded="false">
                        Products <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="{{ $location->putActive(
                                'catalogue.staff.products.index'
                            ) }}">
                            <a href="{{ route('products.index') }}">
                                View all
                            </a>
                        </li>
                        <li class="{{ $location->putActive(
                                'catalogue.staff.products.create'
                            ) }}">
                            <a href="{{ route('products.create') }}">
                                Create new
                            </a>
                        </li>
                        <li class="{{ $location->putActive(
                                'catalogue.staff.products.images.index'
                            ) }}">
                            <a href="{{ route('catalogue.staff.products.images.index') }}">
                                Images
                            </a>
                        </li>
                        <li class="{{ $location->putActive(
                                'catalogue.staff.tags.index'
                            ) }}">
                            <a href="{{ route('tags.index') }}">
                                Tags
                            </a>
                        </li>
                        <li class="{{ $location->putActive(
                                'catalogue.staff.categories.index'
                            ) }}">
                            <a href="{{ route('categories.index') }}">
                                Categories
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown
                    {{ $location->putActive('staff.orders') }}">
                    <a href="#" class="dropdown-toggle"
                       data-toggle="dropdown"
                       role="button"
                       aria-haspopup="true"
                       aria-expanded="false">
                        Orders <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="{{ $location->putActive(
                                'shopping.staff.orders.index'
                            ) }}">
                            <a href="{{ route('orders.index') }}">
                                View all
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown
                    {{ $location->putActive('staff.tools') }}">
                    <a href="#" class="dropdown-toggle"
                       data-toggle="dropdown"
                       role="button"
                       aria-haspopup="true"
                       aria-expanded="false">
                        Tools <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="/staff/logs">
                                Logs
                            </a>
                        </li>
                        <li class="{{ $location->putActive(
                                'staff.tools.telegram'
                            ) }}">
                            <a href="{{ route('telegram.index') }}">
                                <span class="icon icon-telegram"></span>
                                Telegram
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

    </div>
</nav>
