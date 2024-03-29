package net.nextcluster.driver.resource.platform.impl;

import net.nextcluster.driver.resource.platform.DownloadablePlatform;
import net.nextcluster.driver.resource.platform.PlatformArgs;

public class NukkitPlatform extends DownloadablePlatform implements PlatformArgs {
    public NukkitPlatform() {
        super("PROXY", "NUKKIT", "https://ci.opencollab.dev/job/NukkitX/job/Nukkit/job/master/lastSuccessfulBuild/artifact/target/nukkit-1.0-SNAPSHOT.jar");
    }

    @Override
    public String[] args() {
        return new String[0];
    }
}
