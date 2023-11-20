<div class="card card-body">
    <h5 class="card-title m-0"><i class="fas fa-puzzle-piece" style="margin-right: 10px;"></i> Setup</h5>
    <hr>
    <div class="card-text">
        <p>After cloning/downloading the repository, follow the setups below to setup the application.</p>
        <ol>
            <li class="mb-4">
                <strong>Server Configuration</strong>
                <ol class="mt-2">
                    <li>For this application we suggest to create a vhost and set the root directory to <code>/your/project/directory/public</code> directory.</li>
                    <li>Set the correct file permissions and directory owner for your project directory.</li>
                </ol>
            </li>
            <li class="mb-4">
                <strong>Application Configuration</strong>
                <ol class="mt-2">
                    <li>Create <code>.env</code> file on the root directory. Copy the content of <code>sample.env</code> file to <code>.env</code> file.</li>
                    <li>You can bring any changes to the configuration file as you require.</li>
                    <li>Install all the Composer packages using the following command.
                        <p class="p-2"><code>composer install</code></p>
                    </li>
                    <li><strong>Important!</strong> For this project there are some directories we ignore in the repository. To create those directories, use the following command.
                        <p class="p-2"><code>mkdir cache storage logs</code></p>
                    </li>
                </ol>
            </li>
            <li>
                <strong>Database Configuration</strong>
                <ol class="mt-2">
                    <li>Create a database. You can name the database anything you want.</li>
                    <li>Import the SQL file in the <code>database-queries</code> directory to the database.</li>
                    <li>In the <code>.env</code> file set the database configuration values.
<code>
<pre class="py-3">
DB_HOST=localhost
DB_USER=
DB_PASS=
DB_NAME=
</pre>
</code>
                    </li>
                </ol>
            </li>
        </ol>
    </div>
</div>